<template>
  <form class="card mb-12" id="form-position" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{ position?.uuid ? `Edit Jabatan : ${position.name}` : "Tambah Assets"  }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openForm = false, $parent.selected = undefined)"
        >
          <i class="las la-times-circle"></i>
          Batal
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-10">
            <label for="code" class="form-label required"> Kelompok : </label>
            <select2 name="profile" id="profile"
              class="form-control" required autoComplete="off" v-model="form.profile" >
              <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
            </select2>
          </div>
        </div>
        <div class="col-6">
          <div class="mb-10">
            <label for="code" class="form-label required"> Jenis Asset : </label>
            <select2 name="jenisasset_id" id="jenisasset_id"
              class="form-control" required autoComplete="off" v-model="form.jenisasset_id" >
              <option v-for="jenisasset in jenisassets" :value="jenisasset.id" :key="jenisasset.uuid">{{ jenisasset.nama }}</option>
            </select2>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-1">
          <label for="name" class="form-label required"> No: </label>
          <p>1</p>
        </div>
        <div class="col-3">
          <div class="mb-8">
            <label for="name" class="form-label required"> Nama Asset : </label>
            <input type="text" name="nm_asset" id="nm_asset" placeholder="Nama Asset"
              class="form-control" required autoComplete="off" v-model="form.name" />
          </div>
        </div>
        <div class="col-1" style="width: 10%">
          <div class="mb-8">
            <label for="code" class="form-label required"> Tahun : </label>
            <input type="text" name="tahun" id="tahun" placeholder="Tahun"
              class="form-control" required autoComplete="off" v-model="form.code" />
          </div>
        </div>
        <div class="col-2" style="width: 20.6%">
          <div class="mb-8">
            <label for="code" class="form-label required"> Kelompok : </label>
            <select2 name="kelompok_id" id="kelompok_id"
              class="form-control" required autoComplete="off" v-model="form.kelompok_id" >
              <option v-for="kelompok in kelompoks" :value="kelompok.id" :key="kelompok.uuid">{{ kelompok.nama }}</option>
            </select2>
          </div>
        </div>
        <div class="col-2">
          <div class="mb-8">
            <label for="code" class="form-label required"> Tarif Penyusutan : </label>
            <input type="text" name="tarif" id="tarif" placeholder="Kode"
              class="form-control" required autoComplete="off" v-model="form.tarif" />
          </div>
        </div>
        <div class="col-1" style="width: 11%">
          <div class="mb-8">
            <label for="code" class="form-label required"> Jumlah : </label>
            <input type="text" name="jumlah" id="jumlah" placeholder="Kode"
              class="form-control" required autoComplete="off" v-model="form.jumlah" />
          </div>
        </div>
        <div class="col-1">
          <label for="code" class="form-label required"> Aksi : </label>
          <button type="submit" class="btn btn-sm btn-clean btn-icon">
          <i class="la la-plus fs-2x kt-font-success"></i>
          </button>
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
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: position } = useQuery(
      ["position", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-position"), 100);
        return axios.get(`/position/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: data => form.value = data,
        onSettled: () => KTApp.unblock("#form-position"),
      }
    );

    const { mutate: submit } = useMutation((data) => axios.post(selected ? `/position/${selected}/update` : '/position/store', data).then(res => res.data), {
      onMutate: () => {
        KTApp.block("#form-position");
      },
      onError: (error) => {
        toastr.error(error.response.data.message);
      },
      onSettled: () => {
        KTApp.unblock("#form-position");
      }
    });

    const { data: kelompoks } = useQuery(["kelompoks"], () => axios.get("/kelompok/get").then((res) => res.data), {
    placeholderData: []
    });
    const { data: profiles } = useQuery(["profiles"], () => axios.get("/profile/get").then((res) => res.data), {
    placeholderData: []
    });
    const { data: jenisassets } = useQuery(["jenisassets"], () => axios.get("/jenisasset/get").then((res) => res.data), {
    placeholderData: []
    });

    return {
      position,
      kelompoks,
      profiles,
      jenisassets,
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
      const data = new FormData(document.getElementById("form-position"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/position/paginate"], { exact: true });
        }
      });
    }
  }
};
</script>

<style>
</style>