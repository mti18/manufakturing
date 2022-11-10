<template>
    <form class="card mb-12" id="form-kota" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ kota?.uuid ? `Edit Kota atau Kabupaten : ${kota.name}` : "Tambah Kota atau Kabupaten"  }}
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
            <div class="mb-8">
              <label for="name" class="form-label required"> Nama Kota atau Kabupaten: </label>
              <input type="text" name="nm_kab_kota" id="nm_kab_kota" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nm_kab_kota" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Provinsi: </label>
              <select2 name="provinsi_id" id="provinsi_id"
                class="form-control" required autoComplete="off" v-model="form.provinsi_id" >
                <option v-for="provinsi in provinsis" :value="provinsi.id" :key="provinsi.uuid">{{ provinsi.nm_provinsi }}</option>
              </select2>
            </div>
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
  
      const { data: kota } = useQuery(
        ["kota", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-kota"), 100);
          return axios.get(`/kota/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-kota"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/kota/${selected}/update` : '/kota/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-kota");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-kota");
        }
      });

      const { data: provinsis } = useQuery(["provinsis"], () => axios.get("/provinsi/get").then((res) => res.data), {
      placeholderData: []
      });
  
      return {
        kota,
        submit,
        form,
        provinsis,
        queryClient
      }
    },
    methods: {
      
      onUpdateFiles(files) {
        this.file = files;
      },
      onSubmit() {
        const vm = this;
        const data = new FormData(document.getElementById("form-kota"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/kota/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>