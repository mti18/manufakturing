<template>
    <form class="card mb-12" id="form-assetgroup" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ assetgroup?.uuid ? `Edit Asset Group : ${assetgroup.nm_asset_group}` : "Tambah Asset Group "  }}
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
              <label for="nm_asset_group" class="form-label required"> Nama Asset Group : </label>
              <input type="text" name="nm_asset_group" id="nm_asset_group" placeholder="Nama Asset group"
              class="form-control" required autoComplete="off" v-model="form.nm_asset_group" />
            </div>
          </div>
          <div class="col-5">
            <div class="mb-8">
              <label for="line" class="form-label required "> Line : </label>
              <input type="text" name="line" id="line" placeholder="Line"
              class="form-control" required autoComplete="off" v-model="form.line" />
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
  
      const { data: assetgroup } = useQuery(
        ["assetgroup", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-assetgroup"), 100);
          return axios.get(`/assetgroup/${selected}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected,
          cacheTime: 0,
          onSuccess: data => form.value = data,
          onSettled: () => KTApp.unblock("#form-assetgroup"),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected ? `/assetgroup/${selected}/update` : '/assetgroup/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-assetgroup");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-assetgroup");
        }
      });
  
      return {
        assetgroup,
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
        const data = new FormData(document.getElementById("form-assetgroup"));
        this.submit(data, {
          onSuccess: (data) => {
            toastr.success(data.message);
            vm.$parent.openForm = false;
            vm.$parent.selected = undefined;
            vm.queryClient.invalidateQueries(["/assetgroup/paginate"], { exact: true });
          }
        });
      }
    }
  };
  </script>
  
  <style>
  </style>
  