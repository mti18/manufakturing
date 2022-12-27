<template>
    <form class="card mb-12" id="form-penyusutan" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ penyusutan?.uuid ? `Edit Penyusutan : ` : "Tambah Penyusutan"  }}
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
          <div class="col-6" >
            <div class="mb-8">
              <label for="nm_golongan" class="form-label required"> Nama Golongan : </label>
              <input type="text" name="nm_golongan" id="nm_golongan" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nm_golongan" />
            </div>
          </div>
            <div class="col-6">
            <div class="mb-8">
              <label for="metode_penyusutan" class="form-label required "> Metode Penyusutan : </label>
              <input type="text" name="metode_penyusutan" id="metode_penyusutan" placeholder="Metode"
                class="form-control" required autoComplete="off" v-model="form.metode_penyusutan" />
            </div>
          </div>
          
            <div class="row">
            <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Masa </label>
            <div class="input-group">
              <input
                type="number"
                name="masa"
                id="masa"
                min="0"
                placeholder="Masa"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.masa"
              />
              <div class="input-group-append">
                <span class="input-group-text">Tahun</span>
              </div>
            </div>
          </div>
        </div>
          <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required">
              Rate Penyusutan :
            </label>
            <div class="input-group">
              <input
                type="text"
                name="tarif"
                id="tarif"
                placeholder="Tarif Penyusutan"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.rate"
              />
              <div class="input-group-append">
                <span class="input-group-text">%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm ms-auto mt-8 d-block">
              <i class="las la-save"></i>
              Simpan
            </button>
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
  
      const { data: penyusutan } = useQuery(
        ["penyusutan", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-penyusutan"), 100);
          return axios.get(`/penyusutan/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-penyusutan"),
        }
      );
     
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/penyusutan/${selected}/update` : '/penyusutan/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-penyusutan");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-penyusutan");
        }
      });
  
      return {

        penyusutan,
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
        const data = new FormData(document.getElementById("form-penyusutan"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/penyusutan/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>
  