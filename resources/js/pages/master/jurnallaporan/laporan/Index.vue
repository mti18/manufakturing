<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
        <div class="card" >
      <div class="card-header card-header-stretch">
        <h3 class="card-title">Penutupan Periode</h3>
        <div class="card-toolbar">
          <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
            <li class="nav-item" v-for="(item, index) in dataTutup">
              <a
                class="nav-link"
                :class="item.class"
                data-bs-toggle="tab"
                @click="change(index)"
                :href="'#kt_tab_pane_' + item.tahun"
                >{{ item.tahun }}</a
              >
            </li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="tab-content" id="myTabContent">
          <div
            v-for="(item, index) in dataTutup"
            class="tab-pane fade"
            :class="item.class"
            :id="'kt_tab_pane_' + item.tahun"
            role="tabpanel"
          >
            <div class="body">
              <div class="mt-5">
                <!---->
                <div class="table-responsive">
                  <table
                    class="
                      table table-hover table-rounded table-striped
                      border
                      gy-7
                      gs-7
                    "
                  >
                    <thead class="border">
                      <tr
                        class="
                          fw-bold
                          fs-6
                          text-gray-800
                          border-bottom-2 border-gray-200
                        "
                      ></tr>
                    </thead>
                    <tbody class="border">
                      <div class="sheet-group pt-5">
                        <div class="sheet-group-head"><h3>                      
                          Pendapatan
                          <i class="fas fa-list-alt"></i>
                        </h3></div>
                      </div>
                      <tr v-for="item in item.data_debit">
                        <td>{{ item.nm_account }}</td>
                        <div class="form-group py-0">
                          <input
                            type="text"
                            placeholder="Kas"
                            disabled="disabled"
                            :value="
                              item.total
                                .toFixed(2)
                                .replace(/\d(?=(\d{3})+\.)/g, '$&,')
                            "
                            class="form-control"
                          />
                        </div>
                      </tr>

                      <div class="sheet-group">
                        <div class="sheet-group-head d-flex">
                          <h3 class="me-2">Pengeluaran</h3>
                          <i class="la la-undo-alt" style="font-size: 20px"></i>
                        </div>
                      </div>
                      <tr v-for="item in item.data_kredit">
                        <td>{{ item.nm_account }}</td>
                        <div class="form-group py-0">
                          <input
                            type="text"
                            placeholder="Kas"
                            disabled="disabled"
                            :value="
                              item.total
                                .toFixed(2)
                                .replace(/\d(?=(\d{3})+\.)/g, '$&,')
                            "
                            class="form-control"
                          />
                        </div>
                      </tr>
                      <tr>
                        <td>
                          <h5>
                            {{
                              item.nom > 0
                                ? "laba  Neto Sebelum Pajak"
                                : "rugi Neto"
                            }}
                          </h5>
                          <br>
                          <br>
                          
                          <h5>
                            {{
                              'Pembulatan'
                            }}
                          </h5>
                        </td>
                        <td align="center" class="col-6 col-md-3">
                          <div class="form-group py-0">
                            <input
                              type="text"
                              placeholder="Laba Neto Sebelum Pajak"
                              disabled="disabled"
                              :value="
                                Math.abs(item.nom)
                                  .toFixed(2)
                                  .replace(/\d(?=(\d{3})+\.)/g, '$&,')
                              "
                              class="form-control"
                            />
                            <label for=""></label>
                            <input
                              type="text"
                              placeholder="Laba Neto Sebelum Pajak"
                              disabled="disabled"
                              v-model="item.nom_bulat"
                              class="form-control"
                            />
                          </div>
                        </td>
                      </tr>
                      <template v-if="item.nom >= 0">
                        <tr>
                          <td>
                            Pajak Penghasilan 25% x 50% x
                            {{
                              Math.abs(Math.round(item.nom / 10000) * 10000)
                                .toFixed(2)
                                .replace(/\d(?=(\d{3})+\.)/g, "$&,")
                            }}
                          </td>
                          <td align="center" class="col-6 col-md-3">
                            <div class="form-group py-0">
                              <input
                                type="text"
                                disabled
                                v-model="item.nom_pajak"
                                placeholder="Pajak Penghasilan"
                                class="form-control"
                              />
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <h5>
                              {{
                                item.nom > 0
                                  ? "laba  Neto Sesudah Pajak"
                                  : "rugi Neto"
                              }}
                            </h5>
                          </td>
                          <td align="center" class="col-6 col-md-3">
                            <div class="form-group py-0">
                              <input
                                type="text"
                                placeholder="Sum Pajak Penghasilan"
                                v-model="item.nom_potong"
                                disabled="disabled"
                                class="form-control"
                              />
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <div class="sheet-group-head d-flex">
                            <h3 class="me-2">Pembayaran Pajak</h3>
                          </div>
                        </tr>
                        <tr>
                          <td>Pajak Yang Sudah Dibayar</td>
                          <td align="center" class="col-6 col-md-3">
                            <div class="form-group py-0">
                              <input
                                type="text"
                                @input.prevent="bayarpajak(index)"
                                v-model="item.nom_sudah_bayar"
                                oninput="this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                placeholder="sudah bayar"
                                class="form-control"
                              />
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><h5>Pajak Dikreditkan</h5></td>
                          <td align="center" class="col-6 col-md-3">
                            <div class="form-group py-0">
                              <input
                                type="text"
                                v-model="item.nom_belum_bayar"
                                placeholder="Laba Neto Setelah Pajak"
                                disabled="disabled"
                                class="form-control"
                              />
                            </div>
                          </td>
                        </tr>
                      </template>
                      <tr>
                        <div class="sheet-group-head d-flex">
                          <h3 class="me-2">Akun</h3>
                        </div>
                      </tr>
                      <tr>
                        <td>Akun Pajak</td>
                        <td align="center" class="col-6 col-md-3">
                          <div class="form-group py-0">
                            <select2
                              class="form-control"
                              v-model="formRequest.pajak"
                    
                            >
                              <option value="" disabled selected>Tahun</option>
                              <option
                                v-for="item in pajak"
                                :value="item.id"
                                :data="item.id"
                                :key="item.uuid"
                              >
                                {{ item.kode_account }} - {{ item.nm_account }}
                              </option>
                            </select2>
                          </div>
                        </td>
                      </tr>
                      
                      <tr>
                        <td><h5>Akun Yang dipotong</h5></td>
                        <td align="center" class="col-6 col-md-3">
                          <div class="form-group py-0" >
                            <select2
                              class="form-control"
                              v-model="formRequest.bank"
                            >
                              <option value="" disabled selected>Tahun</option>
                              <option
                                v-for="item in bank"
                                :value="item.id"
                                :data="item.id"
                                :key="item.uuid"
                              >
                                {{ item.kode_account }} - {{ item.nm_account }}
                              </option>
                            </select2>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Akun Induk Modal / Ekuitas</td>
                        <td align="center" class="col-6 col-md-3">
                          <div class="form-group py-0">
                            <select2
                              class="form-control"
                              v-model="formRequest.account_id"
                             
                            >
                              <option value="" disabled selected>Pilih</option>
                              <option
                                v-for="item in account"
                                :value="item.id"
                                :key="item.uuid"
                              >
                                {{ item.kode_account }} - {{ item.nm_account }}
                              </option>
                            </select2>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
                <!---->
                <!---->
                <!---->
                <!---->
                <!---->
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer ms-4">
            <button v-if="!openFilters" class="btn btn-danger me-2"  
            @click="openFilters = true">
            <span class="fas fa-window-close"></span>
            batal</button>
            <button class="btn btn-success" @click="tutupPeriode()">
              <span class="fas fa-store-slash"></span>
              tutup akun
            </button>
        </div>
      </div>
      </template>
    </section>
  </template>
  
  <script>
  import { ref, h } from "vue";
  import { useQueryClient } from "vue-query";
  import { useQuery } from "vue-query";
  import axios from "@/libs/axios";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();


  import Filters from "./Filters.vue";
  
  
  export default {
    components: {
     Filters,
    },
    data(){
      return{
        tahun: "",
        bank: [],
        data: [],
        tahuns: [],
        url: "",
        parent_id: [],
        dataTutup: {
            data_debit: [],
            data_kredit: [],
            nom: 0,
        },
        debit: 0,
        kredit: 0,
        status: false,
        formRequest: {
            tahun: "",
            account_id: "",
      },
      }
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openFilters = ref(true);
      
      const { data: account } = useQuery(["accounts" ], () =>
        axios.get("/laporan/get1").then((res) => res.data)
      );
      const { data: child } = useQuery(["accounts", 'nodes'], () =>
        axios.get("/laporan/child").then((res) => res.data)
      );
      const { data: pajak } = useQuery(["accounts", "pajak"], () =>
        axios.get("/laporan/showpajak").then((res) => res.data)
      );
      const { data: bank } = useQuery(["accounts", "bank"], () =>
        axios.get("/laporan/showbank").then((res) => res.data)
      );
  
      const columns = [
        columnHelper.accessor("action", {
          header: "Data Buku Besar",
          cell: (cell) => openFilters.value ? null : h('div', { class: 'col-12' }, [
              h('div', {innerHTML:cell.getValue()})
            ],),
        }),
        
      ]
  
      return {
        child,
        bank,
        pajak,
        account,
        selected,
        openFilters,
        columns,
        queryClient,
      }
    },
    methods:{
      tutupPeriode() {
      var app = this;

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "Apakah Anda Yakin Menutup Periode ini?",
          text: "Silahkan check terlebih dahulu, jangan sampai lupa dan menyesal, anda tidak bisa mengedit jurnal setelah aperiode ditutup",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Ya, saya sudah mengechecknya",
          cancelButtonText: "No, cancel!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
             if(app.formRequest.pajak == undefined  || app.formRequest.bank == undefined || app.formRequest.account_id == undefined){
              toastr.error('akun pajak, bank dan Ekiitas harus diisi');
            } else {
            app.$http
              .post("laporan/tutup", {
                form: app.formRequest,
                data: app.dataTutup,
              })
              .then((res) => {
                toastr.success('berhasil menutup periode', 'Sukses');
                app.openFilters = true;
                 app.formRequest = {
                  tahun: "",
                };
              });
            }
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelled",
              "Your imaginary file is safe :)",
              "error"
            );
          }
        });
    },
    change(index) {
      var app = this;
      app.dataTutup['0'].class = "";
      app.tahun = app.dataTutup[index].tahun;
    },
      
    },
     mounted() {
    
  },
  }
  </script>
  
  <style>
  .tambah{
    padding: auto;
    margin-left: 30px;
  }
  .back{
    padding: auto;
    margin-right:  30px;
  }
  
  </style>