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
              <label for="name" class="form-label required"> Perusahaan: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.profile_id" />
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label required"> Supplier: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.supplier_id" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label required"> Tanggal: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.tanggal" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Account: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.account_id" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Total: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nama" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Total yang harus dibayar: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nama" />
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label required"> Jenis Pembayaran: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.jenis_pembayaran" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Account: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.accbiaya_id" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Uang Muka: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.uangmuka" />
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Sisa belum dibayar: </label>
              <input type="text" name="nama" id="nama" placeholder="Nama"
                class="form-control" required autoComplete="off" v-model="form.nama" />
            </div>
          </div>
          <div class="col-12">
            <div class="mb-8">
              <label for="name" class="form-label required"> Bayar: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                  <input type="text" name="diskon" id="diskon" v-bind="config"
                    class="form-control" required autoComplete="off" v-model="form.bayar" />
              </div>
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Keterangan: </label>
              <textarea name="diskon" id="diskon" v-bind="config"
                class="form-control" required autoComplete="off" v-model="form.keterangans" />
            </div>
          </div>
          <div class="col-12">
            
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
        tahuns: [],
        supplier_id: this.$parent.selected,
        profile_id: this.$parent.selected
      };
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({});

      const { data: accounts  } = useQuery(["accounts"], () =>
      axios.get("/account/get").then((res) => res.data)
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