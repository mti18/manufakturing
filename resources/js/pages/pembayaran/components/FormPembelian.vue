<template>
    <form class="card mb-12" id="form-pembayaran" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ pembayaran?.uuid ? `Edit Pembayaran : ${pembayaran.name}` : "Tambah Pembayaran"  }}
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
        </div>
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="name" class="form-label required"> Tanggal: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tanggal" id="tanggal" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tanggal" />
              </div>
            </div>

    
            <div class="mb-8" v-if="form.jenis_pembayaran=='Tunai'">
              <label for="name" class="form-label required"> Account: </label>
              <select2 name="account_id" id="account_id" disabled
                class="form-control" autoComplete="off" v-model="form.account_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8" v-if="form.jenis_pembayaran=='Transfer'">
              <label for="name" class="form-label required"> Account: </label>
              <select2 name="account_id" id="account_id"
                class="form-control" autoComplete="off" v-model="form.account_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8" v-if="form.jenis_pembayaran=='Cek'">
              <label for="name" class="form-label required"> Account: </label>
              <select2 name="account_id" id="account_id"
                class="form-control" autoComplete="off" v-model="form.account_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8" v-if="form.jenis_pembayaran==null">
              <label for="name" class="form-label required"> Account: </label>
              <select2 name="account_id" id="account_id" disabled
                class="form-control" autoComplete="off" v-model="form.account_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>


            <div class="mb-8">
              <label for="name" class="form-label required"> Total: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                  <input type="text" name="total" id="total" v-bind="config"
                    class="form-control" required autoComplete="off" v-model="form.total" />
              </div>
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Total yang harus dibayar: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                  <input type="text" name="totalbayar" id="totalbayar" v-bind="config"
                    class="form-control" required autoComplete="off" v-model="form.total" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Jenis Pembayaran : </label>
              <select2 name="jenis_pembayaran" id="jenis_pembayaran"
                class="form-control" required autoComplete="off" v-model="form.jenis_pembayaran" >
                <option value="" disabled>Pilih</option>
                <option value="Cek">Cek</option>
                <option value="Transfer">Transfer</option>
                <option value="Tunai">Tunai</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Account: </label>
              <select2 name="accbiaya_id" id="accbiaya_id"
                class="form-control" autoComplete="off" v-model="form.accbiaya_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Uang Muka: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                  <input type="text" name="uangmuka" id="uangmuka" v-bind="config"
                    class="form-control" required autoComplete="off" v-model="form.uangmuka" />
              </div>
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Sisa belum dibayar: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                  <input type="text" name="sisabayar" id="sisabayar" v-bind="config"
                    class="form-control" required autoComplete="off" v-model="form.sisabayar" />
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="mb-8">
              <label for="name" class="form-label required"> Bayar: </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                  <input type="text" name="bayar" id="bayar" v-bind="config"
                    class="form-control" required autoComplete="off" v-model="form.bayar" />
              </div>
            </div>
            <div class="mb-8">
              <label for="name" class="form-label required"> Keterangan: </label>
              <textarea name="keterangan" id="keterangan" v-bind="config" placeholder="keterangan"
                class="form-control" required autoComplete="off" v-model="form.keterangan" />
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
        profile_id: this.$parent.selected,
      };
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({
        pembayaran: "Pembelian",
      });

      const { data: accounts  } = useQuery(["accounts"], () =>
      axios.get("/account/get").then((res) => res.data)
    );
      const { data: profiles } = useQuery(["profiles"], () => 
        axios.get("/profile/get").then((res) => res.data)
      );
      const { data: suppliers } = useQuery(["suppliers"], () => 
        axios.get("/supplier/get").then((res) => res.data)
      );
  
      const { data: pembayaran } = useQuery(
        ["pembayaran", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-pembayaran"), 100);
          return axios.get(`/pembayaran/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-pembayaran"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/pembayaran/${selected}/update` : '/pembayaran/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-pembayaran");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-pembayaran");
        }
      });
  
      return {
        pembayaran,
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
        const data = new FormData(document.getElementById("form-pembayaran"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/pembayaran/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>