<template>
    <form class="card mb-12" id="form-asset" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ asset?.uuid ? `Edit Asset : ${asset.nm_asset}` : "Tambah Asset "  }}
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
          <div class="col-5">
            <div class="mb-8">
              <label for="nm_asset" class="form-label required"> Nama Asset : </label>
              <input type="text" name="nm_asset" id="nm_asset" placeholder="Nama Asset"
              class="form-control" required autoComplete="off" v-model="form.nm_asset" />
            </div>
          </div>
          <div class="col-5">
            <div class="mb-8">
              <label for="number" class="form-label required "> Number : </label>
              <input type="" name="number" id="number" placeholder="Number"
              class="form-control" required autoComplete="off" v-model="form.number" />
            </div>
          </div>
          <div class="col-3">
          <div class="mb-8">
            <label for="nm_golongan" class="form-label required">
              Nama Golongan :
            </label>
            <select2
              class="form-control"
              name="golongan_id"
              placeholder="Pilih Nama Golongan"
              id="golongan_id"
              v-model="form.golongan_id"
              required
            >
              <option value="" disabled>Pilih Golongan</option>
              <option
                v-for="item in golongan"
                :value="item.id"
                :key="item.id"
              >
               {{item.metode_penyusutan}} - {{ item.nm_golongan}}
              </option>
            </select2>
          </div>
        </div>
        <div class="col-md-6">
                <div class="mb-4">
                  <label class="required form-label">Price :</label>
                  <input
                    v-money="{
                      thousands: '.',
                      decimal: ',',
                      precision: 2,
                    }"
                  
                    type="text"
                    class="form-control"
                    placeholder="Price"
                    name="price"
                    v-model="form.price"
                    required
                  />
                </div>
              </div>
        </div>
        <div class="row">
        <div class="col-3">
          <div class="mb-8">
            <label for="nm_asset_group" class="form-label required">
              Nama Asset Group :
            </label>
            <select2
              class="form-control"
              name="asset_group_id"
              placeholder="Pilih Nama Golongan"
              id="asset_group_id"
              v-model="form.asset_group_id"
              required
            >
              <option value="" disabled>Pilih Asset Group</option>
              <option
                v-for="item in assetgroup"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_asset_group}}
              </option>
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

      const { data: golongan } = useQuery(["golongans"], () =>
      axios.get("/golongan/get").then((res) => res.data.data)
    );
  
      const { data: assetgroup } = useQuery(["asset_groups"], () =>
      axios.get("/assetgroup/get").then((res) => res.data.data)
    );
  
      const { data: asset } = useQuery(
        ["asset", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-asset"), 100);
          return axios.get(`/asset/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-asset"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/asset/${selected}/update` : '/asset/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-asset");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-asset");
        }
      });
  
      return {
        assetgroup,
        golongan,
        asset,
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
        const data = new FormData(document.getElementById("form-asset"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/asset/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>
  