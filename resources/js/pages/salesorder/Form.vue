<template>
    <form class="card mb-12" id="form-salesorder" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ salesorder?.uuid ? `Edit Jabatan : ${salesorder.name}` : "Tambah Sales Order"  }}
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
                class="form-control" autoComplete="off" v-model="form.diketahui_oleh" >
                <option disabled>Pilih</option>
                <option v-for="user in users" :value="user.id" :key="user.uuid">{{ user.name }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Bukti Pemesanan : </label>
              <input type="text" name="bukti_pesan" id="bukti_pesan" placeholder="Bukti Pemasanan"
                class="form-control" autoComplete="off" v-model="form.bukti_pesan" />
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Tanggal Pesan : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tgl_pesan" id="tgl_pesan" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_pesan" />
              </div>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Tanggal Pengiriman : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tgl_pengiriman" id="tgl_pengiriman" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_pengiriman" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label for="code" class="form-label required"> Jumlah Paket : </label>
              <input type="text" name="jumlah_paket" id="jumlah_paket" placeholder="Jumlah Paket"
                class="form-control" autoComplete="off" v-model="form.jumlah_paket" />
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Jenis Pembayaran : </label>
              <select2 name="jenis_pembayaran" id="jenis_pembayaran"
                class="form-control" required autoComplete="off" v-model="form.jenis_pembayaran" >
                <option value="Tunai">Tunai</option>
                <option value="Cek">Cek</option>
                <option value="Transfer">Transfer</option>
                <option value="Free">Free</option>
              </select2>
            </div>
            <div class="mb-8" v-if="form.jenis_pembayaran=='Transfer'">
              <label for="code" class="form-label required"> Bank : </label>
              <select2 name="account_id" id="account_id"
                class="form-control" required autoComplete="off" v-model="form.account_id" >
                <option disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id" :key="account.uuid">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Jatuh Tempo : </label>
              <div class="input-group">
                <input type="text" name="tempo" id="tempo" placeholder="Kode"
                class="form-control" required autoComplete="off" v-model="form.tempo" />
                <div class="input-group-append"><span class="input-group-text">Hari</span></div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm me-auto mt-8 d-block">
              <i class="la la-arrow-circle-right"></i>
              Next
            </button>
          </div>
        </div>
      </div>
    </form>

    <!-- BUTTON LIST -->
    <div class="card-header">
        <div class="card-title w-100">
          <button
            type="button"
            class="btn btn-primary btn-sm btn-elevate btn-icon-sm me-2"
            @click="($parent.openForm = false, $parent.selected = undefined)"
          >
            <i class="las la-camera-retro"></i>
            Scan QR Code
          </button>
          <button
            type="button"
            class="btn btn-primary btn-sm btn-elevate btn-icon-sm"
            @click="($parent.openForm = false, $parent.selected = undefined)"
          >
            <i class="las la-pen"></i>
            Typing Code
          </button>
          <button
            type="button"
            class="btn btn-primary btn-sm btn-elevate btn-icon-sm pojok"
            @click="($parent.openForm = false, $parent.selected = undefined)"
          >
            <i class="las la-copy"></i>
            Salin Pesanan
          </button>
        </div>
    </div>


    <form v-if="!!selected" class="card mb-12" id="form-salesorderdetail" @submit.prevent="onSubmitDetail" >
      <div class="table-responsive">
        <table class="table border">
          <thead>
            <tr class="fw-bold fs-6 text-gray-800 border align-middle">
              <!-- <th rowspan="2">Tipe Barang</th>
              <th rowspan="2">Nama Barang</th> -->
              <th colspan="2">Qty</th>
              <th rowspan="2">Harga</th>
              <th rowspan="2">Diskon</th>
              <th rowspan="2">Jumlah</th>
              <th rowspan="2">Keterangan</th>
              <th rowspan="2">Aksi</th>
            </tr>
            <tr class="fw-bold fs-6 text-gray-800 border">
              <th>Volume</th>
              <th>Satuan</th>
            </tr>
          </thead>
          <tbody class="border">
          <tr v-for="detail in form.details" :key="detail.id">
            <!-- <td>
              <select2 name="tipe" id="tipe"
                class="form-control" required autoComplete="off" v-model="form.tipe" >
                <option disabled>Pilih</option>
                <option value="Barang Mentah">Barang Mentah</option>
                <option value="Barang Jadi">Barang Jadi</option>
              </select2>
            </td>
            <td>
              <div v-if="form.tipe=='Barang Mentah'">
                <select2 name="barang_id" id="supplier" 
                  class="form-control" required autoComplete="off" v-model="form.barang_id" >
                  <option disabled>Pilih</option>
                  <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.uuid">{{ supplier.nama }}</option>
                </select2>
              </div>
              <div v-if="form.tipe=='Barang Jadi'">
                <select2 name="supplier_id" id="supplier"
                  class="form-control" required autoComplete="off" v-model="form.supplier_id" >
                  <option disabled>Pilih</option>
                  <option v-for="user in users" :value="user.id" :key="user.uuid">{{ user.name }}</option>
                </select2>
              </div>
            </td> -->
            <td>
              <input type="text" name="volume" id="volume" @input.prevent="hitungbarang()"
                class="form-control" required autoComplete="off" v-model="detail.volume" />
            </td>
            <td>
              <input type="text" name="nm_satuan" id="nm_satuan"
                class="form-control" required autoComplete="off" v-model="detail.nm_satuan" />
            </td>
            <td>
              <input type="text" name="harga" id="harga" @input.prevent="hitungbarang()"
                class="form-control" required autoComplete="off" v-model="detail.harga" />
            </td>
            <td>
              <input type="text" name="diskon" id="diskon" @input.prevent="hitungbarang()"
                class="form-control" required autoComplete="off" v-model="detail.diskon" />
            </td>
            <td>
              <input type="text" name="jumlah" id="jumlah"
                class="form-control" required autoComplete="off" v-model="detail.jumlah" />
            </td>
            <td>
              <textarea type="text" name="keterangan" id="keterangan"
                class="form-control" autoComplete="off" v-model="detail.keterangan" />
            </td>
            <td></td>
          </tr>
          </tbody>
        </table>
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm me-auto mt-8 d-block">
              <i class="la la-arrow-circle-right"></i>
              Tambah Barang
            </button>
        </div>
      </div>
    </form>


    <form v-if="!!selected" class="card mb-12" id="form-salesorder" @submit.prevent="onSubmitMore">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="code" class="form-label required"> Keterangan : </label>
              <textarea rows="10" name="keterangan" id="keterangan" placeholder="Keterangan"
                class="form-control" required autoComplete="off" v-model="form.ketarangan" >
              </textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="mt-8">
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label for="code" class="form-label">Total</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="total" id="total" @input.prevent="hitungdiskon()"
                        class="form-control" required autoComplete="off" v-model="form.total" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row radio-inline">
                  <div class="col-md-2">
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <label for="code" class="form-label">
                        <input type="radio" name="tipe_diskon" id="tipe_diskon" @input.prevent="hitungdiskon()"
                        required autoComplete="off" value="persen" v-model="form.tipe_diskon" />
                        Persen (%)
                      </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <label for="code" class="form-label">
                        <input type="radio" name="tipe_diskon" id="tipe_diskon" @input.prevent="hitungdiskon()"
                        required autoComplete="off" value="rupiah" v-model="form.tipe_diskon" />
                        Rupiah (Rp)
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label for="code" class="form-label">Diskon</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group" v-if="form.tipe_diskon=='persen'">
                      <input type="text" name="diskon2" id="diskon2" @input.prevent="hitungdiskon()"
                        class="form-control" required autoComplete="off" v-model="form.diskon2" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                    <div class="input-group" v-if="form.tipe_diskon=='rupiah'">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="diskon2" id="diskon2" @input.prevent="hitungdiskon()"
                        class="form-control" required autoComplete="off" v-model="form.diskon2" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label">Uang Muka</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="uang_muka" id="uang_muka" @input.prevent="uangmuka()"
                        class="form-control" required autoComplete="off" v-model="form.uang_muka" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label"> PPH : </label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <input type="text" name="diskon" id="diskon" 
                        class="form-control" required autoComplete="off" v-model="form.pph" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label for="code" class="form-label"> PPN : </label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <input type="text" name="ppn" id="ppn" placeholder="Pilih Tanggal"
                        class="form-control" required autoComplete="off" v-model="form.ppn" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label for="code" class="form-label"> Netto : </label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="netto" id="netto"
                        class="form-control" required autoComplete="off" v-model="form.netto" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm me-auto mt-8 d-block">
              <i class="la las la-save"></i>
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
    setup(props) {
      const queryClient = useQueryClient();
      const form = ref({});
      const selected = ref(props.selected);
      console.log(selected.value)
  
      const { data: salesorder = {details: []}, refetch } = useQuery(
        ["salesorder", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-salesorder"), 100);
          return axios.get(`/salesorder/${selected.value}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-salesorder"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected.value ? `/salesorder/${selected.value}/update` : '/salesorder/store', data).then(res => res.data), {
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
      const { data: accounts } = useQuery(["accounts"], () => axios.get("/account/get").then((res) => res.data), {
      placeholderData: []
      });
  
      return {
        salesorder,
        profiles,
        accounts,
        suppliers,
        users,
        submit,
        form,
        queryClient,
        selected,
        refetch
      }
    },
    methods: {

      clearFormData(){
        this.form = {
          profile_id: '', 
          supplier_id: '', 
          tgl_pesan: '', 
          tgl_pengiriman: '', 
          jenis_pembayaran: '',
          diketahui_oleh: '',
          account_id: '', 
          tempo: 0,
          diskon: 0
        };
      },

      hitungbarang(){
        var app=this;

        var jumlah = app.detail.volume * app.detail.harga;
        var diskon = (jumlah * app.detail.diskon) / 100;
        var total = jumlah - diskon || 0;
        app.detail.jumlah = (total);
        app.form.total = app.form.jumlah;
      },

      hitungdiskon(){
        var app=this;


        if (app.form.tipe_diskon=='persen') {
          var jumlah = app.form.jumlah;
          var diskon = (jumlah * app.form.diskon) / 100;
          var hasildiskon = jumlah - diskon || 0;
          app.form.netto = (hasildiskon);
        }
        else{
          var jumlah = app.form.jumlah;
          var hasildiskon = jumlah - app.form.diskon || 0;
          app.form.netto = (hasildiskon);
        }
      },

      uangmuka(){
        var app=this;

        var netto = app.form.netto-app.form.uang_muka || 0;
        app.form.netto = (netto);
      },

      onUpdateFiles(files) {
        this.file = files;
      },
      onSubmit() {
        const vm = this;
        const data = new FormData(document.getElementById("form-salesorder"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.selected = data.data.uuid;
            vm.$parent.openForm = true;
            vm.$parent.selected = undefined;
            vm.refetch();
            vm.queryClient.invalidateQueries(["/salesorder/paginate"], { exact: true });
          }
        });
      },
      // onSubmitDetail() {
      //   const vm = this;
      //   const data = new FormData(document.getElementById("form-salesorder"));
      //   this.submit(data, {
      //     onSuccess: (data) => {
      //       toastr.success(data.message);
      //       vm.selected = data.data.id;
      //       vm.$parent.openForm = true;
      //       vm.$parent.selected = undefined;
      //       refetch();
      //       vm.queryClient.invalidateQueries(["/salesorder/paginate"], { exact: true });
      //     }
      //   });
      // }
      
    },

    mounted() {
      this.clearFormData();
    },
  };
  </script>
  
  <style scoped> 



  .pojok {
    margin-left: 540px;
  }


  .table {
    vertical-align: middle;
    text-align: center;
  }


  </style>