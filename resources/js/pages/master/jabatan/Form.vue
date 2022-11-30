<template>
  <form class="card mb-12" id="form-position" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            position?.uuid
              ? `Edit Jabatan : ${position.name}`
              : "Tambah Jabatan"
          }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openForm = false), ($parent.selected = undefined)"
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
            <label for="name" class="form-label required"> Nama : </label>
            <input
              type="text"
              name="name"
              id="name"
              placeholder="Nama"
              class="form-control"
              required
              autoComplete="off"
              @input.prevent="
                getCode();
                kd();
              "
              v-model="form.name"
            />
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required"> Kode : </label>
            <input
              type="text"
              name="code"
              id="code"
              placeholder="Kode"
              class="form-control"
              required
              readonly
              autoComplete="off"
              v-model="form.code"
            />
          </div>
        </div>
        <div class="col-12">
          <button
            type="submit"
            class="btn btn-primary btn-sm ms-auto mt-8 d-block"
          >
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
    },
  },

  data() {
    return {
      code: null,
    };
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const { data: position } = useQuery(
      ["position", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-position"), 100);
        return axios.get(`/position/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-position"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/position/${selected}/update` : "/position/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-position");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-position");
        },
      }
    );

    return {
      position,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    kd() {
      var vm = this;
      var myString = this.form.name;
      var splits = myString.split(" ");
      var codes = "";

      for (var i = 0; i < splits.length; i++) {
        if (splits[i][0]) {
          codes += splits[i].split("")[0];
        }
      }
      if (!vm.selected) {
        if (codes != "") {
          vm.form.code =
            codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "-" + vm.code;
          $(".codes").val(vm.form.code);
        } else {
          vm.code = "";
          vm.form.code = vm.code;
          $(".codes").val(vm.form.code);
        }
      } else {
        if (codes != "") {
          vm.form.code =
            codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "-" + vm.code;
          $(".codes").val(vm.form.code);
        } else {
          vm.code = "";
          vm.form.code = vm.code;
          $(".codes").val(vm.form.code);
        }
      }
    },

    getCode() {
      this.$http
        .get("position/getcode")
        .then((res) => {
          console.log(res.data);
          this.code = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-position"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/position/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
