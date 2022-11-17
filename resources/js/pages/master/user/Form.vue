<template>
  <form class="card mb-12" id="form-user" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{ user?.uuid ? `Edit User : ${user.name}` : "Tambah User"  }}
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
        <div class="col-4">
          <label for="name" class="form-label required"> Avatar : </label>
          <file-upload :files="selected && form?.avatar ? `/${form.avatar}` : file" :allow-multiple="false"
            v-on:updatefiles="onUpdateFiles" labelIdle='Drag & Drop your files or <span class="filepond-label-action">Browse</span>' required
            :accepted-file-types="['image/*']"></file-upload>
        </div>
        <div class="col-8">
          <div class="row">
            <div class="col-6">
              <div class="mb-8">
                <label for="name" class="form-label required"> Nama : </label>
                <input type="text" name="name" id="name" placeholder="Nama"
                  class="form-control" required autoComplete="off" v-model="form.name" />
              </div>
            </div>
            <div class="col-6">
              <div class="mb-8">
                <label for="email" class="form-label required"> Email : </label>
                <input type="text" name="email" id="email" placeholder="Email"
                  class="form-control" required autoComplete="off" v-model="form.email" />
              </div>
            </div>
            <div class="col-6">
              <div class="mb-8">
                <label for="whatsapp" class="form-label required"> Whatsapp : </label>
                <input type="text" name="whatsapp" id="whatsapp" placeholder="Whatsapp"
                  class="form-control" required autoComplete="off" v-model="form.whatsapp" />
              </div>
            </div>
            <div class="col-6">
              <div class="mb-8">
                <label for="nip" class="form-label required"> NIP : </label>
                <input type="text" name="nip" id="nip" placeholder="NIP"
                  class="form-control" required autoComplete="off" v-model="form.nip" />
              </div>
            </div>
            <div class="col-6">
              <div class="mb-8">
                <label for="password" class="form-label required"> Password : </label>
                <input type="text" name="password" id="password" placeholder="Password"
                  class="form-control" :required="!selected" autoComplete="off" v-model="form.password" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="separator mb-8"></div>
            <div class="col-12">
              <div class="mb-8">
                <label for="address" class="form-label required"> Alamat : </label>
                <input type="text" name="address" id="address" placeholder="Alamat"
                  class="form-control" required autoComplete="off" v-model="form.address" />
              </div>
            </div>
            <div class="col-4">
              <div class="mb-8">
                <label for="birth_date" class="form-label required"> Tanggal Lahir : </label>
                <datepicker class="form-control" name="birth_date" placeholder="Pilih Tanggal" id="birth_date" v-model="form.birth_date" required></datepicker>
              </div>
            </div>
            <div class="col-4">
              <div class="mb-8">
                <label for="level" class="form-label required"> Level : </label>
                <select2 class="form-control" name="level" placeholder="Pilih Level" id="level" v-model="form.level" required>
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
                  <option value="finance">Finance</option>
                  <option value="marketing">Marketing</option>
                </select2>
              </div>
            </div>
            <div class="col-4">
              <div class="mb-8">
                <label for="status" class="form-label required"> Status : </label>
                <select2 class="form-control" name="status" placeholder="Pilih Status" id="status" v-model="form.status" required>
                  <option value="tetap">Tetap</option>
                  <option value="kontrak">Kontrak</option>
                  <option value="magang">Magang</option>
                </select2>
              </div>
            </div>
            <div class="col-4">
              <div class="mb-8">
                <label for="user_group_uuid" class="form-label required"> User Group : </label>
                <select2 class="form-control" name="user_group_uuid" placeholder="Pilih User Group" id="user_group_uuid" v-model="form.user_group_uuid" required>
                  <option v-if="user?.uuid"></option>
                  <option v-for="group in userGroups" :value="group.uuid" :key="group.uuid">{{ group.name }}</option>
                </select2>
              </div>
            </div>
            <div class="col-4">
              <div class="mb-8">
                <label for="position_uuid" class="form-label required"> Jabatan : </label>
                <select2 class="form-control" name="position_uuid" placeholder="Pilih Jabatan" id="position_uuid" v-model="form.position_uuid" required>
                  <option v-if="user?.uuid"></option>
                  <option v-for="position in positions" :value="position.uuid" :key="position.uuid">{{ position.name }}</option>
                </select2>
              </div>
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

    const file = ref([]);
    const { data: user } = useQuery(
      ["user", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-user"), 100);
        return axios.get(`/user/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSettled: () => KTApp.unblock("#form-user"),
        onSuccess: data => form.value = data
      }
    );

    const { mutate: submit } = useMutation((data) => axios.post(selected ? `/user/${selected}/update` : '/user/store', data).then(res => res.data), {
      onMutate: () => {
        KTApp.block("#form-user");
      },
      onError: (error) => {
        toastr.error(error.response.data.message);
      },
      onSettled: () => {
        KTApp.unblock("#form-user");
      }
    });

    const { data: userGroups } = useQuery(["user-groups"], () => axios.get("/user-group/get").then((res) => res.data), {
      placeholderData: []
    });

    const { data: positions } = useQuery(["positions"], () => axios.get("/position/get").then((res) => res.data), {
      placeholderData: []
    });

    return {
      user,
      file,
      submit,
      form,
      userGroups,
      positions,
      queryClient
    }
  },
  methods: {
    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-user"));
      data.append("avatar", this.file[0].file);
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.queryClient.invalidateQueries(["/user/paginate"]);
        }
      });
    }
  }
};
</script>

<style>
</style>