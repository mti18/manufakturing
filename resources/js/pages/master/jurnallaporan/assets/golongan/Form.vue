<template>
    <form class="card mb-12" id="form-golongan" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ golongan?.uuid ? `Edit Golongan : ${golongan.nm_golongan}` : "Tambah Golongan"  }}
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
              <label for="nm_golongan" class="form-label required"> Nama : </label>
              <input type="text" name="nm_golongan" id="nm_golongan" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nm_golongan" />
            </div>
            <div class="mb-8">
              <label for="metode_penyusutan" class="form-label required "> Metode Penyusutan : </label>
              <input type="text" name="metode_penyusutan" id="metode_penyusutan" placeholder="Metode"
                class="form-control" required autoComplete="off" v-model="form.metode_penyusutan" />
            </div>
            <div class="mb-8">
              <label for="masa" class="form-label required "> Masa : </label>
              <input type="number" min="0" name="masa" id="masa" placeholder="Masa"
                class="form-control" required autoComplete="off" v-model="form.masa" />
            </div>
            <div class="mb-8">
              <label for="rate" class="form-label required "> Rate : </label>
              <input type="number" min="0" name="rate" id="rate" placeholder="Rate"
                class="form-control" required autoComplete="off" v-model="form.rate" />
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
  
      const { data: golongan } = useQuery(
        ["golongan", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-golongan"), 100);
          return axios.get(`/golongan/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-golongan"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/golongan/${selected}/update` : '/golongan/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-golongan");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-golongan");
        }
      });
  
      return {
        golongan,
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
        const data = new FormData(document.getElementById("form-golongan"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/golongan/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>
  