<template>
  <form class="card mb-12" id="form-jenisasset" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{ jenisasset?.uuid ? `Edit Jabatan : ${jenisasset.name}` : "Tambah Jabatan"  }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openUtangPiutang = false, $parent.selected = undefined)"
        >
          <i class="las la-times-circle"></i>
          Batal
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Perusahaan: </label>
            <select2 name="profile_id" id="profile"
              class="form-control" disabled  autoComplete="off" v-model="profile_id" >
              <option value="" disabled></option>
              <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
            </select2>
          </div>        
        </div>  
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Supplier: </label>
            <select2 name="supplier_id" id="supplier"
              class="form-control" required autoComplete="off" v-model="supplier_id" >
              <option value="" disabled>Pilih</option>
              <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.uuid">{{ supplier.nama }}</option>
            </select2>
          </div>
        </div>
        <div class="mb-8">
          <label for="name" class="form-label required"> Account: </label>
          <select2 name="account_id" id="account_id"
            class="form-control" autoComplete="off" v-model="form.account_id" >
            <option value="" disabled>Pilih</option>
            <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
          </select2>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary btn-sm ms-auto mt-8 d-block">
            <i class="las la-save"></i>
            Simpan
          </button>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import { ref } from "vue";
import { useQuery, useMutation } from "vue-query";
import axios from "@/libs/axios";
import { useQueryClient } from "vue-query";

export default {
  props: {
    selected: {
      type: String,
      default: null,
    }
  },
  data() {
    return {
      supplier_id: this.$parent.selected,
      profile_id: this.$parent.selected,
    };
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: accounts  } = useQuery(["accounts"], () =>
      axios.get("/account/get").then((res) => res.data)
    );
    const { data: profiles } = useQuery(["profiles"], () => 
      axios.get("/profile/get").then((res) => res.data)
    );
    const { data: suppliers } = useQuery(["suppliers"], () => 
      axios.get("/supplier/get").then((res) => res.data)
    );

    const { data: jenisasset } = useQuery(
      ["jenisasset", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-jenisasset"), 100);
        return axios.get(`/jenisasset/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: data => form.value = data,
        onSettled: () => KTApp.unblock("#form-jenisasset"),
      }
    );

    const { mutate: submit } = useMutation((data) => axios.post(selected ? `/jenisasset/${selected}/update` : '/jenisasset/store', data).then(res => res.data), {
      onMutate: () => {
        KTApp.block("#form-jenisasset");
      },
      onError: (error) => {
        toastr.error(error.response.data.message);
      },
      onSettled: () => {
        KTApp.unblock("#form-jenisasset");
      }
    });

    return {
      jenisasset,
      profiles,
      suppliers,
      accounts,
      submit,
      form,
      queryClient
    }
  },
  methods: {
    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-jenisasset"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/jenisasset/paginate"], { exact: true });
        }
      });
    }
  }
};
</script>

<style>
</style>