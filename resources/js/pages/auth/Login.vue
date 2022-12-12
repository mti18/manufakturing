<template>
  <form
    class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
    @submit.prevent="onSubmit"
  >
    <div class="text-center mb-11">
      <h1 class="text-dark fw-bolder mb-3">Masuk</h1>
      <div class="text-gray-500 fw-semibold fs-6">
        {{ $page.props.setting.name }}
      </div>
    </div>
    <div class="fv-row mb-8 fv-plugins-icon-container">
      <label for="identity" class="form-label">Email / NIP / Whatsapp</label>
      <input
        type="text"
        placeholder="Email / NIP / Whatsapp"
        name="identity"
        id="identity"
        autocomplete="off"
        class="form-control bg-transparent"
        v-model="auth.identity"
        @input="auth.type = undefined"
      />
    </div>

    <div
      v-if="auth.type === 'email'"
      class="fv-row mb-3 fv-plugins-icon-container"
    >
      <label for="identity" class="form-label">Password</label>
      <input
        type="password"
        placeholder="Password"
        name="password"
        autocomplete="off"
        class="form-control bg-transparent"
        v-model="auth.password"
      />
      <div class="forget d-inline">
        <Link :href="route('gantipassword')">Ganti Password</Link>
      </div>
    </div>
    <div
      v-else-if="auth.type === 'nip'"
      class="fv-row mb-3 fv-plugins-icon-container"
    >
      <label for="identity" class="form-label">Password</label>
      <input
        type="password"
        placeholder="Password"
        name="password"
        autocomplete="off"
        class="form-control bg-transparent"
        v-model="auth.password"
      />
    </div>
    <div
      v-else-if="auth.type === 'phone'"
      class="fv-row mb-3 fv-plugins-icon-container"
    >
      <label for="identity" class="form-label">Kode OTP</label>
      <v-otp-input
        ref="otpInput"
        input-classes="otp-input form-control bg-transparent text-center"
        separator=" "
        :num-inputs="6"
        :should-auto-focus="true"
        :is-input-num="true"
        @on-complete="onCompleteOtp"
      />
    </div>
    <div class="d-grid mb-10 mt-10">
      <button
        type="submit"
        class="btn btn-primary"
        :disabled="status === 'loading'"
        :data-kt-indicator="status === 'loading' ? 'on' : 'off'"
      >
        <span class="indicator-label">Masuk</span>
        <span class="indicator-progress">
          Mohon tunggu...
          <span
            class="spinner-border spinner-border-sm align-middle ms-2"
          ></span>
        </span>
      </button>
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
    const auth = ref({
      otp: "",
    });
    const status = ref("idle");
    return { auth, status, queryClient };
  },
  methods: {
    onCompleteOtp(value) {
      this.auth.otp = value;
    },
    onSubmit() {
      if (this.auth.type) this.login();
      else this.checkCredential();
    },
    login() {
      const vm = this;
      vm.status = "loading";
      const data = { type: vm.auth.type };
      if (vm.auth.type === "email") {
        data.email = vm.auth.identity;
        data.password = vm.auth.password;
      } else if (vm.auth.type === "nip") {
        data.nip = vm.auth.identity;
        data.password = vm.auth.password;
      } else if (vm.auth.type === "phone") {
        data.phone = vm.auth.identity;
        data.otp = vm.auth.otp;
      }
      vm.axios
        .post("/auth/login", data)
        .then((res) => {
          localStorage.setItem("auth-token", res.data.token);
          vm.queryClient.invalidateQueries(["user"]);
          Inertia.visit("/");
        })
        .catch((error) => {
          toastr.error(error.response.data.message);
          vm.status = "error";
        });
    },
    checkCredential() {
      const vm = this;
      vm.status = "loading";
      vm.axios
        .post("/secure/check-credential", {
          identity: vm.auth.identity,
        })
        .then((res) => {
          vm.auth = {
            ...vm.auth,
            ...res.data.data,
          };
          vm.status = "success";
          if (vm.auth.type === "phone") vm.getOtp();
        })
        .catch((error) => {
          toastr.error(error.response.data.message);
          vm.status = "error";
        });
    },
    getOtp() {
      const vm = this;
      vm.status = "loading";
      vm.axios
        .post("/secure/get-otp", { phone: vm.auth.phone })
        .then((res) => {
          vm.status = "success";
          toastr.success(res.data.data.message);
        })
        .catch((error) => {
          toastr.error(error.response.data.message);
          vm.status = "error";
        });
    },
    ResetPassword() {},
  },
};
</script>

<style></style>
