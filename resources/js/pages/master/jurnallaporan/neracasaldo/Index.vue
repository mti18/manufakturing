<template>
    <section>
      <Filters v-if="openFilters" />
      <template v-else>
          <div class="card">
            <div class="card-header">
                <h1 class="mt-6">Neraca Saldo</h1>
              <div class="card-title ">
                <button v-if="!openFilters" type="button" class="back btn btn btn-danger btn-sm ms-auto " @click="openFilters = true">
                  <i class="fas fa-angle-left"></i>
                  BACK
                </button>
                <button  type="button" class="back btn btn btn-success btn-sm ms-auto " >
                  <i class="las la-file-excel"></i>
                  CETAK
                </button>
              </div>
            </div>
            <div>
            <transition
              enter-active-class="animated slideInDown"
              leave-active-class="animated slideOutRight"
            >
              <div v-if="showneraca" class="card card-custom">
                <div class="table-responsive">
                  <table
                    class="
                      table table-hover table-rounded table-striped
                      border
                      gy-7
                      gs-7
                    "
                  >
                    <thead>
                      <tr
                        class="
                          fw-semibold
                          fs-6
                          text-gray-800
                          border-bottom-2 border-gray-200
                        "
                      >
                        <th class="text-center" style="width: 150px">Kode Akun</th>
                        <th class="text-center">Nama Akun</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Kredit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in data">
                        <td class="text-center">{{ item.kode_account.rupiah() }}</td>
                        <td>{{ item.nm_account }}</td>
                        <td class="text-center">
                          {{ item.sum > 0 ? item.sum.rupiah() : "-" }}
                        </td>
                        <td class="text-center">
                          {{ item.sum > 0 ? "-" : Math.abs(item.sum).rupiah() }}
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"><h4 class="text-end">Total</h4></td>
                        <td class="font-weight-bold text-center">
                          {{ debit.rupiah() }}
                        </td>
                        <td class="font-weight-bold text-center">
                          {{ kredit.rupiah() }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </transition>
          </div>
          </div>
        
      </template>
    </section>
  </template>
  
  <script>
  import { ref, h } from "vue";
  import { useQueryClient } from "vue-query";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();

  import Filters from "./Filters.vue";
  import { useDelete } from "@/libs/hooks";
  
  export default {
    components: {
     Filters, 
    },
    data(){
      return{
        data: [],
        showneraca: false,
        debit: 0,
        kredit: 0,
        account: [],
        formRequest: {
          bulan: '',
          tahun: '',
        }
      }
    },
    setup() {
      const queryClient = useQueryClient();
      const selected = ref();
      const openFilters = ref(true);
  
      const columns = [
        
      ]
  
      return {

        selected,
        openFilters,
        columns,
      }
    },
    methods:{
       

     getAccount(){
        var app = this;
        app.axios.get('account/show').then((res) => {
        app.account = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
      
    },
     mounted() {
    var app = this;
    app.getAccount();
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