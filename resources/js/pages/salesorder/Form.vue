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
                class="form-control" required autoComplete="off" v-model="form.diketahui_oleh" >
                <option disabled>Pilih</option>
                <option v-for="user in users" :value="user.id" :key="user.uuid">{{ user.name }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label for="code" class="form-label required"> Bukti Pemesanan : </label>
              <input type="text" name="bukti_pesan" id="bukti_pesan" placeholder="Bukti Pemasanan"
                class="form-control" required autoComplete="off" v-model="form.bukti_pesan" />
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
                class="form-control" required autoComplete="off" v-model="form.jumlah_paket" />
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
              <select2 name="profile_id" id="profile"
                class="form-control" required autoComplete="off" v-model="form.profile_id" >
                <option disabled>Pilih</option>
                <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
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
            <i class="las la-pencil-square-o"></i>
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
    <form class="card mb-12" >
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr class="fw-bold fs-6 text-gray-800">
              <th rowspan="2">Nama Barang</th>
              <th colspan="2">Qty</th>
              <th rowspan="2">Harga</th>
              <th rowspan="2">Diskon</th>
              <th rowspan="2">Jumlah</th>
              <th rowspan="2">Keterangan</th>
              <th rowspan="2">Aksi</th>
            </tr>
            <tr class="fw-bold fs-6 text-gray-800">
              <th>Volume</th>
              <th>Satuan</th>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td  style="width: 25%">
              <select2 name="supplier_id" id="supplier"
                class="form-control" required autoComplete="off" v-model="form.supplier_id" >
                <option disabled>Pilih</option>
                <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.uuid">{{ supplier.nama }}</option>
              </select2>
            </td>
            <td style="width: 12.5%">
              <input type="text" name="volume" id="volume"
                class="form-control" required autoComplete="off" v-model="form.volume" />
            </td>
            <td style="width: 12.5%">
              <input type="text" name="satuan" id="satuan"
                class="form-control" required autoComplete="off" v-model="form.satuan" />
            </td>
            <td style="width: 10%">
              <input type="text" name="harga" id="harga"
                class="form-control" required autoComplete="off" v-model="form.harga" />
            </td>
            <td style="width: 10%">
              <input type="text" name="diskon" id="diskon"
                class="form-control" required autoComplete="off" v-model="form.diskon" />
            </td>
            <td style="width: 10%">
              <input type="text" name="jumlah" id="jumlah"
                class="form-control" required autoComplete="off" v-model="form.jumlah" />
            </td>
            <td style="width: 15%">
              <textarea type="text" name="keterangan" id="keterangan"
                class="form-control" required autoComplete="off" v-model="form.keterangan" />
            </td>
            <td style="width: 5%">
              <input type="text" name="volume" id="volume"
                class="form-control" required autoComplete="off" v-model="form.volume" />
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </form>


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
            <i class="las la-copy"></i>
            Batal
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label for="code" class="form-label required"> Keterangan : </label>
              <textarea rows="8" name="profile_id" id="profile"
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
                      <input type="text" name="total" id="total" placeholder="Pilih Tanggal"
                        class="form-control" required autoComplete="off" v-model="form.total" />
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
                    <div class="input-group">
                      <input type="text" name="diskon" id="diskon" placeholder="Pilih Tanggal"
                        class="form-control" required autoComplete="off" v-model="form.diskon" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
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
                      <input type="text" name="uang_muka" id="uang_muka" placeholder="Pilih Tanggal"
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
                      <input type="text" name="diskon" id="diskon" placeholder="Pilih Tanggal"
                        class="form-control" required autoComplete="off" v-model="form.diskon" />
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
                      <input type="text" name="netto" id="netto" placeholder="Pilih Tanggal"
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

      // hitungbarang(){
      //   var app=this;
        
      //   var index = app.kelompoks.findIndex((cat) => cat.id==e);
      //   app.form.tarif = app.kelompoks[index].tarif;
      //   app.formdetail.jumlah = app.decimal(total);
      // },

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
  
  <style scoped> 

  .pojok {
    margin-left: 580px;
  }
  .table {
    text-align: center;
  }
  </style>