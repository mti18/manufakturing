<template>
  <form
    class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
    @submit.prevent="onSubmit"
  >
    <div class="text-center mb-11">
      <h1 class="text-dark fw-bolder mb-3">Ganti Password</h1>
    </div>

    <div class="fv-row mb-8 fv-plugins-icon-container">
      <label for="oldpassword" class="form-label">Masukkan Password Lama</label>
      <input
        type="text"
        placeholder="Password Lama"
        name="oldpassword"
        id="oldpassword"
        autocomplete="off"
        class="form-control bg-transparent"
        v-model="auth.oldpassword"
      />
    </div>

    <div class="fv-row mb-8 fv-plugins-icon-container">
      <label for="newpassword" class="form-label">Masukkan Password Baru</label>
      <input
        type="text"
        placeholder="Password Baru"
        name="newpassword"
        id="newpassword"
        autocomplete="off"
        class="form-control bg-transparent"
        v-model="auth.newpassword"
      />
    </div>

    <div class="fv-row mb-8 fv-plugins-icon-container">
      <label for="newpassword_confirmation" class="form-label"
        >Konfirmasi Password</label
      >
      <input
        type="text"
        placeholder="Konfirmasi Password"
        name="newpassword_confirmation"
        id="newpassword_confirmation"
        autocomplete="off"
        class="form-control bg-transparent"
        v-model="auth.newpassword_confirmation"
      />
    </div>
    <div class="d-grid mb-10 mt-10">
      <button
        type="submit"
        class="btn btn-primary"
        :disabled="status === 'loading'"
        :data-kt-indicator="status === 'loading' ? 'on' : 'off'"
      >
        <span class="indicator-label">Masukkan</span>

        <span class="indicator-progress">
          Mohon tunggu...
          <span
            class="spinner-border spinner-border-sm align-middle ms-2"
          ></span>
        </span>
      </button>
      <Link :href="route('login')">Kembali Halaman Login</Link>
    </div>
  </form>
</template>

<script>
import { ref } from "vue";
import { useQueryClient } from "vue-query";
import { Inertia } from "@inertiajs/inertia";

export default {
  setup() {
    const queryClient = useQueryClient();
    const auth = ref({});
    const status = ref("idle");
    return { auth, status, queryClient };
  },
  methods: {
    onSubmit() {
      if (this.auth.type) this.login();
      else this.changePassword();
    },
    changePassword() {
      const vm = this;

      vm.status = "loading";
      vm.axios.post("/auth/gantipassword", vm.auth).catch((error) => {
        toastr.error(error.response.data.message);
        vm.status = "error";
      });
    },
  },
};
</script>

<style></style>
