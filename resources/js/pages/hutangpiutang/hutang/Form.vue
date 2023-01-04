<template>
    <form class="card mb-12" id="form-hutang" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ hutang?.uuid ? `Edit Hutang : ${hutang.type}` : "Tambah Hutang"  }}
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
              <label for="profile_id" class="form-label required"> Profile : </label>
              <input type="text" name="profile_id" id="profile" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.profile_id" />
            </div>
          </div>
            <div class="col-6">
            <div class="mb-8">
              <label for="metode_penyusutan" class="form-label required "> Account : </label>
              <select2
              class="form-control"
              name="account_id"
              id="account"
              v-model="form.account_id"
              placeholder="Pilih Account"
              required
            > 
              <option value="" disabled>Pilih Account</option>
              <option
                v-for="item in account"
                :value="item.id" :key="item.uuid"
              >
              {{ item.nm_account }}
              </option>
             
            </select2>
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

      const { data: account } = useQuery(["accounts"], () =>
      axios.get("/masterjurnal/child").then((res) => res.data)
    );
  
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
        account,
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
      },
      getAccount() {
      setTimeout(() => {
        var app = this;
        var app = app.form.account_id;
        axios
          .get(`masterjurnal/child`)
          .then((res) => {
            app.child= res.data;
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "gagal");
          });
      }, 500);
    },
    }
  };
  </script>
  
  <style>
  </style>
  