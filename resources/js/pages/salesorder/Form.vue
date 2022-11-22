<template>
    <form class="card mb-12" id="form-salesorder" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ salesorder?.uuid ? `Edit Jabatan : ${salesorder.name}` : "Tambah Jabatan"  }}
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
              <label for="code" class="form-label required"> Nama Perusahaan : </label>
              <select2 name="profile_id" id="profile"
                class="form-control" required autoComplete="off" v-model="form.profile_id" >
                <option disabled>Pilih</option>
                <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
              </select2>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label for="code" class="form-label required"> Nama Pemesan : </label>
              <select2 name="supplier_id" id="supplier"
                class="form-control" required autoComplete="off" v-model="form.supplier_id" >
                <option disabled>Pilih</option>
                <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.uuid">{{ supplier.nama }}</option>
              </select2>
            </div>
          </div>
        </div>
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="code" class="form-label required"> Diketahui Oleh : </label>
              <select2 name="diketahui_oleh" id="diketahui_oleh"
                class="form-control" required autoComplete="off" v-model="form.diketahui_oleh" >
                <option disabled>Pilih</option>
                <option v-for="user in users" :value="user.id" :key="user.uuid">{{ user.name }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Bukti Pemesanan : </label>
              <input type="text" name="jumlah_paket" id="jumlah_paket" placeholder="Bukti Pemasanan"
                class="form-control" required autoComplete="off" v-model="form.jumlah_paket" />
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Tanggal Pesan : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="jumlah_paket" id="jumlah_paket" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_pesan" />
              </div>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Tanggal Pengiriman : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="jumlah_paket" id="jumlah_paket" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_kirim" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label for="code" class="form-label required"> Jumlah Paket : </label>
              <input type="text" name="jumlah_paket" id="jumlah_paket" placeholder="Jumlah Paket"
                class="form-control" required autoComplete="off" v-model="form.jumlah_paket" />
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Pembayaran : </label>
              <select2 name="pembayaran" id="pembayaran"
                class="form-control" required autoComplete="off" v-model="form.pembayaran" >
                <option value="Tunai">Tunai</option>
                <option value="Cek">Cek</option>
                <option value="Transfer">Transfer</option>
                <option value="Free">Free</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Jatuh Tempo : </label>
              <div class="input-group">
                <input type="text" name="jatuh_tempo" id="jatuh_tempo" placeholder="Kode"
                class="form-control" required autoComplete="off" v-model="form.jatuh_tempo" />
                <div class="input-group-append"><span class="input-group-text">Hari</span></div>
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
  
      const { data: salesorder } = useQuery(
        ["salesorder", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-salesorder"), 100);
          return axios.get(`/salesorder/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-salesorder"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/salesorder/${selected}/update` : '/salesorder/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-salesorder");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-salesorder");
        }
      });

      const { data: profiles } = useQuery(["profiles"], () => axios.get("/profile/get").then((res) => res.data), {
      placeholderData: []
      });
      const { data: suppliers } = useQuery(["suppliers"], () => axios.get("/supplier/get").then((res) => res.data), {
      placeholderData: []
      });
      const { data: users } = useQuery(["users"], () => axios.get("/user/get").then((res) => res.data), {
      placeholderData: []
      });
  
      return {
        salesorder,
        profiles,
        suppliers,
        users,
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
        const data = new FormData(document.getElementById("form-salesorder"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/salesorder/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>