<template>
    <form class="card mb-12" id="form-account" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ account?.uuid ? `Edit Account : ${account.nm_account}` : "Tambah Account"  }}
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
              <label for="nm_account" class="form-label required"> Account : </label>
              <input type="text" name="nm_account" id="nm_account" placeholder="Account"
                class="form-control" required autoComplete="off" v-model="form.nm_account" />
            </div>
          </div>
            <div class="col-6">
            <div class="mb-8">
                <label  for="account_type" class="form-label required">Account Type</label>
                    <select2 class="form-control" name="account_type"
                    placeholder="Pilih" v-model="form.account_type">
                        <option value="rill">Rill</option>
                          <option value="nominal">Nominal</option>
                    </select2>
                  </div>
                </div>
          </div>
          <div class="row">
            <div class="col-6">
            <div class="mb-6">
                <label  for="Type" class="form-label required">Type</label>
                    <select2 class="form-control" name="type"
                    placeholder="Pilih" v-model="form.type">
                        <option value="debit">Debit</option>
                          <option value="kredit">Kredit</option>
                    </select2>
                </div>
              </div>
            <div class="col-6">
            <div class="mb-6">
                <label  for="account_header" class="form-label required">Account Header</label>
                    <select2 class="form-control" name="account_header"
                    placeholder="Pilih" v-model="form.account_header">
                        <option value="pajak">Pajak</option>
                          <option value="bank">Bank</option>
                        <option value="hutang">Hutang</option>
                          <option value="piutang">Piutang</option>
                    </select2>
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
  
      const { data: Account } = useQuery(
        ["account", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-account"), 100);
          return axios.get(`/account/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-account"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/account/${selected}/update` : '/account/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-account");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-account");
        }
      });
  
      return {
        Account,
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
        const data = new FormData(document.getElementById("form-account"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.$parent.getData();
            vm.queryClient.invalidateQueries(["/account/paginate"], { exact: true });
          }
        });
      },
    },
  };
  </script>
  
  <style>
  </style>