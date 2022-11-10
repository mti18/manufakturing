<template>
    <form class="card mb-12" id="form-provinsi" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ provinsi?.uuid ? `Edit Provinsi : ${provinsi.name}` : "Tambah Provinsi"  }}
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
              <label for="name" class="form-label required"> Nama Provinsi: </label>
              <input type="text" name="nm_provinsi" id="nm_provinsi" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nm_provinsi" />
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
  
      const { data: provinsi } = useQuery(
        ["provinsi", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-provinsi"), 100);
          return axios.get(`/provinsi/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-provinsi"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/provinsi/${selected}/update` : '/provinsi/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-provinsi");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-provinsi");
        }
      });
  
      return {
        provinsi,
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
        const data = new FormData(document.getElementById("form-provinsi"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/provinsi/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>