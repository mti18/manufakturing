<template>
    <form class="card mb-12" id="form-kelurahan" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ kelurahan?.uuid ? `Edit Kelurahan : ${kelurahan.name}` : "Tambah Kelurahan"  }}
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
              <label for="name" class="form-label required"> Nama Kelurahan: </label>
              <input type="text" name="nm_kelurahan" id="nm_kelurahan" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.name" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Kecamatan: </label>
              <select2 name="kecamatan_id" id="kecamatan_id"
                class="form-control" required autoComplete="off" v-model="form.kecamatan_id" >
                <option v-for="kecamatan in kecamatans" :value="kecamatan.id" :key="kecamatan.uuid">{{ kecamatan.nm_kecamatan }}</option>
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
  
      const { data: kelurahan } = useQuery(
        ["kelurahan", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-kelurahan"), 100);
          return axios.get(`/kelurahan/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-kelurahan"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/kelurahan/${selected}/update` : '/kelurahan/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-kelurahan");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-kelurahan");
        }
      });

      const { data: kecamatans } = useQuery(["kecamatans"], () => axios.get("/kecamatan/get").then((res) => res.data), {
      placeholderData: []
      });
  
      return {
        kelurahan,
        submit,
        form,
        kecamatans,
        queryClient
      }
    },
    methods: {
      
      onUpdateFiles(files) {
        this.file = files;
      },
      onSubmit() {
        const vm = this;
        const data = new FormData(document.getElementById("form-kelurahan"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/kelurahan/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>