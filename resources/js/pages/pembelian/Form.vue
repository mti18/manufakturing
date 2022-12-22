<template>
    <form class="card mb-12" id="form-pembelian" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ pembelian?.uuid ? `Edit Jabatan : ${pembelian.name}` : "Tambah Jabatan"  }}
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
              <label class="form-label required"> Nama Perusahaan : </label>
              <select2 name="profile_id" id="profile" @change="perusahaan($event)"
                class="form-control" required autoComplete="off" v-model="form.profile_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
              </select2>
            </div>
            <div class="detailperusahaan" v-if="form.profile_id != null">
              <div class="mb-8">
                <label class="form-label required"> Alamat : </label>
                <input type="text" name="alamat" id="alamat"
                  class="form-control" autoComplete="off" disabled v-model="form.profiles_alamat" />
              </div>
              <div class="mb-8">
                <label class="form-label required"> NPWP : </label>
                <input type="text" name="npwp" id="npwp"
                  class="form-control" autoComplete="off" disabled v-model="form.profiles_npwp" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Nama Supplier : </label>
              <select2 name="supplier_id" id="supplier"  @change="supplier($event)"
                class="form-control" required autoComplete="off" v-model="form.supplier_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.uuid">{{ supplier.nama }}</option>
              </select2>
            </div>
            <div class="detailsupplier" v-if="form.supplier_id != null">
              <div class="mb-8">
                <label class="form-label required"> Alamat : </label>
                <input type="text" name="no_surat" id="no_surat"
                  class="form-control" autoComplete="off" disabled v-model="form.suppliers_alamat" />
              </div>
              <div class="mb-8">
                <label class="form-label required"> NPWP : </label>
                <input type="text" name="no_surat" id="no_surat"
                  class="form-control" autoComplete="off" disabled v-model="form.suppliers_npwp" />
              </div>
              <div class="mb-8">
                <label class="form-label required"> NPPKP : </label>
                <input type="text" name="no_surat" id="no_surat"
                  class="form-control" autoComplete="off" disabled v-model="form.suppliers_nppkp" />
              </div>
            </div>
          </div>
        </div>
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
        <div class="row">
          <div class="col-12">
            <div class="mb-8">
              <label class="form-label required"> No Surat : </label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" @change="nomor($event)">{{ nomor }}</span>
                </div>
                <input type="text" class="form-control" name="no_surat" required v-model="form.no_surat" placeholder="No Surat">
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Tanggal Permintaan : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tgl_permintaan" id="tgl_permintaan" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_permintaan" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Bukti Permintaan : </label>
              <input type="text" name="bukti_permintaan" id="bukti_permintaan" placeholder="Bukti Pemasanan"  @input.prevent="kd"
                class="form-control" autoComplete="off" v-model="form.bukti_permintaan" />
            </div>
          </div>
        </div>
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
        <div class="row">
          <div class="col-12">
            <div class="mb-8">
              <label class="form-label required"> No Surat Pembelian : </label>
              <input name="no_surat_pembelian" id="no_surat_pembelian"  placeholder=" No Surat Pembelian"
                class="form-control" required autoComplete="off" v-model="form.no_surat_pembelian" />
            </div>
            <div class="mb-8">
              <label class="form-label required"> Diketahui Oleh : </label>
              <select2 name="diketahui_oleh" id="diketahui_oleh"
                class="form-control" autoComplete="off" v-model="form.diketahui_oleh" >
                <option disabled>Pilih</option>
                <option v-for="user in users" :value="user.id" :key="user.uuid">{{ user.name }}</option>
              </select2>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Tanggal PO : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tgl_po" id="tgl_po" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_po" />
              </div>
            </div>
            <div class="mb-8">
              <label class="form-label required"> No PO Pembelian : </label>
              <input type="text" name="no_po_pembelian" id="no_po_pembelian" placeholder="Bukti Pemasanan"
                class="form-control" autoComplete="off" v-model="form.no_po_pembelian" />
            </div>
            <div class="mb-8">
              <label class="form-label required"> No Surat Jalan : </label>
              <input type="text" name="no_surat_jalan" id="no_surat_jalan" placeholder="Bukti Pemasanan"
                class="form-control" autoComplete="off" v-model="form.no_surat_jalan" />
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Jenis Pembayaran : </label>
              <select2 name="jenis_pembayaran" id="jenis_pembayaran"
                class="form-control" required autoComplete="off" v-model="form.jenis_pembayaran" >
                <option value="Tunai">Tunai</option>
                <option value="Kredit">Kredit</option>
              </select2>
            </div>
            <div class="mb-8">
              <label class="form-label required"> Bank : </label>
              <select2 name="account_id" id="account_id"
                class="form-control" autoComplete="off" v-model="form.account_id" >
                <option disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label class="form-label required"> Jatuh Tempo : </label>
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
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
      </div>
    </form>

    <form v-if="!!selected" class="card mb-12" id="form-pembeliandetail" @submit.prevent="onSubmit" >
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
          <tbody class="border align-middle">
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
              <input type="text" name="volume" id="volume"
                class="form-control" required autoComplete="off" v-model="detail.volume" />
            </td>
            <td>
              <input type="text" name="nm_satuan" id="nm_satuan"
                class="form-control" required autoComplete="off" v-model="detail.nm_satuan" />
            </td>
            <td>
              <input type="text" name="harga" id="harga"
                class="form-control" required autoComplete="off" v-model="detail.harga" />
            </td>
            <td>
              <input type="text" name="diskon" id="diskon"
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

      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Keterangan : </label>
              <textarea rows="10" name="keterangan" id="keterangan" placeholder="Keterangan"
                class="form-control" required autoComplete="off" v-model="form.keterangan" >
              </textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="mt-8">
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label">Jumlah Penjualan</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="jml_penjualan" id="jml_penjualan"
                        class="form-control" required autoComplete="off" v-model="form.jml_penjualan" />
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
                      <label class="form-label">
                        <input type="radio" name="tipe_diskon" id="tipe_diskon"
                        required autoComplete="off" value="persen" v-model="form.tipe_diskon" />
                        Persen (%)
                      </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <label class="form-label">
                        <input type="radio" name="tipe_diskon" id="tipe_diskon"
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
                    <label class="form-label"  v-if="form.tipe_diskon != null">Diskon</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group" v-if="form.tipe_diskon=='persen'">
                      <input type="text" name="diskon" id="diskon"
                        class="form-control" required autoComplete="off" v-model="form.diskon" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                    <div class="input-group" v-if="form.tipe_diskon=='rupiah'">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="diskon" id="diskon"
                        class="form-control" required autoComplete="off" v-model="form.diskon" />
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
                      <input type="text" name="uang_muka" id="uang_muka" 
                        class="form-control" required autoComplete="off" v-model="form.uang_muka" />
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
                      <label class="form-label">
                        <input type="radio" name="tipe_pajak" id="tipe_pajak"
                        required autoComplete="off" value="persen"  v-model="form.tipe_pajak" />
                        Persen (%)
                      </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <label class="form-label">
                        <input type="radio" name="tipe_pajak" id="tipe_pajak"
                        required autoComplete="off" value="rupiah" v-model="form.tipe_pajak" />
                        Rupiah (Rp)
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2" v-if="form.tipe_pajak != null">
                    <label class="form-label" style="font-size: small;">Dasar Pengenaan Pajak</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group" v-if="form.tipe_pajak=='persen'">
                      <input type="text" name="pajak" id="pajak"
                        class="form-control" required autoComplete="off" v-model="form.pajak" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                    <div class="input-group" v-if="form.tipe_pajak=='rupiah'">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="pajak" id="pajak"
                        class="form-control" required autoComplete="off" v-model="form.pajak" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label"> PPN : </label>
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
                    <label class="form-label"> Netto : </label>
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

    data() {
      return {
        nomor: null,
      };
    },

    setup(props) {
      const queryClient = useQueryClient();
      const form = ref({
        details: [{}],
      });
      const selected = ref(props.selected);

      const { data: pembelian = {details: []}, refetch } = useQuery(
        ["pembelian", selected, "edit"],
        () => {
          // setTimeout(() => KTApp.block("#form-pembelian"), 100);
          return axios.get(`/pembelian/${selected.value}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected.value,
          cacheTime: 0,
          onSuccess: ({data}) => {
            const datas = {...data.data};
            console.log(datas)
            if (datas.details.length) {
              datas.details.push({});
            }
            form.value = datas;
          },
          onError: error => console.log(error),
          onSettled: () => KTApp.unblock("#form-pembelian"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected.value ? `/pembelian/${selected.value}/update` : '/pembelian/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-pembelian");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-pembelian");
        },
        onSuccess: (data) => {
          const datas = {...data.data};
          if (!datas.details.length) {
            datas.details.push({});
          }
          form.value = datas;
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
      const { data: accounts } = useQuery(["accounts"], () => axios.get("/account/getdata").then((res) => res.data), {
      placeholderData: []
      });

      return {
        pembelian,
        profiles,
        accounts,
        suppliers,
        users,
        submit,
        form,
        queryClient,
        selected,
        refetch,
      }
    },
    methods: {

      perusahaan(e){
        var app=this;
        var index = app.profiles.findIndex((cat) => cat.id==e);
        app.form.profiles_alamat = app.profiles[index].alamat;
        app.form.profiles_npwp = app.profiles[index].npwp;

        if (app.form.profiles_npwp == null) {
          app.form.profiles_npwp = "-";
        }
      },

      supplier(e){
        var app=this;
        var index = app.suppliers.findIndex((cat) => cat.id==e);
        app.form.suppliers_alamat = app.suppliers[index].alamat;
        app.form.suppliers_npwp = app.suppliers[index].npwp;
        app.form.suppliers_nppkp = app.suppliers[index].nppkp;

        if (app.form.suppliers_nppkp == null) {
          app.form.suppliers_nppkp = "-";
        }

        if (app.form.suppliers_npwp == null) {
          app.form.suppliers_npwp = "-";
        }
      },
      
      onUpdateFiles(files) {
        this.file = files;
      },
      onSubmit() {
        const vm = this;
        const data = new FormData(document.getElementById("form-pembelian"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.selected = data.data.uuid;
            vm.$parent.openForm = true;
            vm.$parent.selected = undefined;
            vm.refetch();
            vm.queryClient.invalidateQueries(["/pembelian/paginate"], { exact: true });
          }
        });
      },
      
      kd: _.debounce(function () {
      var vm = this;
      var myString = this.form.bukti_permintaan;
      var splits = myString.split(" ");
      var codes = "";
      for (var i = 0; i < splits.length; i++) {
        if (splits[i][0]) {
          codes = codes + splits[i][0];
        }
      }
      if (this.type == "store") {
        if (codes != "") {
          vm.kode =
            "/SP/VRA-" + codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "/" + vm.bulan + "/" + vm.tahun;
          vm.form.no_surat = vm.kode;
          $(".codes").val(vm.kode);
        } else {
          vm.kode = "";
          vm.form.no_surat = vm.kode;
          $(".codes").val(vm.kode);
        }
      } else {
        if (codes != "") {
          vm.kode =
            "/SP/VRA-" + codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "/" + vm.bulan + "/" + vm.tahun;
          vm.form.no_surat = vm.kode;
            $(".codes").val(vm.kode);
          } else {
            vm.kode = "";
            vm.form.no_surat = vm.kode;
            $(".codes").val(vm.kode);
          }
        }
      }, 1000),

      gettahun() {
        this.$http
          .get("pembelian/gettahun")
          .then((res) => {
            console.log(res.data)
            this.tahun = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },

      getbulan() {
        this.$http
          .get("pembelian/getbulan")
          .then((res) => {
            console.log(res.data)
            this.bulan = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      
      getnomor() {
        this.$http
          .get("pembelian/getnomor")
          .then((res) => {
            console.log(res.data)
            this.nomor = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },

      editGetnomor() {
        this.$http
          .get("pembelian/" + this.selected + "/getnomor")
          .then((res) => {
            console.log(res.data);
            this.nomor = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },
    },
    mounted() {
      if (!this.selected) {
        this.getnomor();
        this.getbulan();
        this.gettahun();
        return;
      }
      this.editGetnomor();
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