<template>
    <form class="card mb-12" id="form-masterjurnal" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ masterjurnal?.uuid ? `Edit Master Jabatan : ${masterjurnal.name}` : "Tambah Master Jabatan"  }}
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
              <label for="name" class="form-label required"> Nama : </label>
              <input type="text" name="name" id="name" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.name" />
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Kode : </label>
              <input type="text" name="code" id="code" placeholder="Kode"
                class="form-control" required autoComplete="off" v-model="form.code" />
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
  
      const { data: masterjurnal} = useQuery(
        ["masterjurnal", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-masterjurnal"), 100);
          return axios.get(`/masterjurnal/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-masterjurnal"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/masterjurnal/${selected}/update` : '/masterjurnal/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-masterjurnal");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-masterjurnal");
        }
      });
  
      return {
        masterjurnal,
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
        const data = new FormData(document.getElementById("form-masterjurnal"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/masterjurnal/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>