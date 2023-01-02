<template>
    <form class="card mb-12" id="form-detail">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ `Detail Jurnal ` }}
          </h3>
          <button
            type="button"
            class="btn btn-light-danger btn-sm ms-auto"
            @click="($parent.openDetail = false, $parent.selected = undefined)"
          >
            <i class="las la-times-circle"></i>
            Batal
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row ms-10" style="">
          
          <table class="table">
              <thead class="">
                      <!--begin::Table row-->
                      <tr
                        class="
                          text-start text-gray-400
                          fw-bold
                          fs-7
                          text-uppercase
                          gs-0
                        "
                      >
                        <th>
                          <h4
                          class="min-w-100px sorting"
                          tabindex="0"
                          aria-controls="kt_table_widget_5_table"
                          rowspan="1"
                          colspan="1"
                          aria-label="Item: activate to sort column ascending"
                          style="width: 100px"
                        >
                          Nama Akun</h4>
                         
                        </th>
                        <th>

                        </th>
                        <th><h4
                          class="pe-3 min-w-100px sorting"
                          tabindex="0"
                          aria-controls="kt_table_widget_5_table"
                          rowspan="1"
                          colspan="1"
                          aria-label="Price: activate to sort column ascending"
                          style="width: 100px"
                          >
                          Price
                        </h4>
                         
                        </th>
                        <th><h4 class="pe-3 min-w-50px sorting"
                          tabindex="0"
                          aria-controls="kt_table_widget_5_table"
                          rowspan="1"
                          colspan="1"
                          aria-label="Status: activate to sort column ascending"
                          style="width: 100px"
                        >
                          Status
                        </h4>
                         
                        </th>
                      </tr>
                    </thead>

                    <tbody class="fw-bold text-gray-600 p-5">
                      <tr v-for="item in form.jurnal_item">
                        <td>
                          <h5 class="text-dark text-hover-primary">
                            {{ item.account.nm_account }}
                          </h5>
                        </td>
                        <td></td>
                        <td>Rp. {{ item.debit }}</td>
                        <td>Rp. {{ item.kredit }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th><h3>Total</h3></th>
                        <th></th>
                        <th >Rp. {{ form.debit }}</th>
                        <th >Rp. {{ form.kredit }}</th>
                      </tr>
                    </tfoot>
                  </table>
        </div>
      </div>
      <div class="card-body">
            <div class="card-title ">
              <h1>
                Bukti
              </h1>
              <h3 class="text-gray-400 mt-1 fw-semibold fs-6">
                Bukti MasterJurnal
              </h3>
            </div>
            <hr>
              <div class="mt-5">
                <div
                  v-for="item in form.bukti_master"
                  class="
                    border border-dashed border-gray-300
                    rounded
                    px-7
                    py-3
                    mb-6
                  "
                >
                  <div
                    class="d-flex flex-stack mb-3"
                    v-if="item.file.split('.')[1] != 'pdf'"
                  >
                    <div class="me-3">
                      <img
                        :src="`/${item.file}`"
                        class="w-50px ms-n1 me-1"
                        :alt="item.file"
                      />
                    </div>
                  </div>
                  <div
                    class="d-flex flex-stack mb-3"
                    v-if="item.file.split('.')[1] ==  'pdf'"
                  >
                    <div class="me-3">
                      <i
                        class="fas fa-file-excel"
                        style="font-size: xx-large; color: darkseagreen"
                      ></i>
                    </div>
                  </div>
                  <div class="d-flex flex-stack">
                    <span class="text-gray-400 fw-bold"
                      >Nama Bukti:
                      <a
                        :href="`/${item.file}`"
                        target="_blank"
                        class="text-gray-800 text-hover-primary fw-bold"
                        >{{ item.file }}</a
                      ></span
                    >
                  </div>
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
      data(){
      return{
        form: {
        bukti_master: [],
      },
       
      }
    },
    setup({ selected }) {
      const queryClient = useQueryClient();
      const form = ref({
        jurnal_item: selected ? [] : [{}]
      });
      
  
      const { data: masterjurnal } = useQuery(
        ["masterjurnal", selected, "detail"],
        () => {
          setTimeout(() => KTApp.block("#form-detail"), 100);
          return axios.get(`/masterjurnal/${selected}/detail`).then((res) => res.data.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-detail"),
        }
      );
  
     
      return {
        form,
        masterjurnal,
        queryClient
        
      }
    },
      methods: {
        
      },
      mounted() {},
    };
  </script>
  
