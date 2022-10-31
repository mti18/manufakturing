<template>
  <div
    class="d-flex flex-column flex-root bg-white"
    id="kt_app_root"
    style="min-height: 100vh"
  >
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
      <div
        class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1"
      >
        <div class="d-flex flex-center flex-column flex-lg-row-fluid">
          <div class="w-lg-500px p-10">
            <slot />
          </div>
        </div>
      </div>
      <div
        class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
        :style="`background-image: linear-gradient(0deg, rgba(24, 16, 28, 0.5), rgba(24, 16, 28, 0.75)), url('${$page.props.setting.background_url}')`"
      >
        <div
          class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100"
        >
          <span class="mb-0 mb-lg-8">
            <img
              alt="Logo"
              :src="$page.props.setting.logo_dark_url"
              class="h-60px h-lg-75px"
            />
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useQuery } from "vue-query";
import axios from "@/libs/axios";
import { Inertia } from "@inertiajs/inertia";

export default {
  setup() {
    const { data: user } = useQuery(["user"], () =>
      axios.get("/auth/user").then((res) => res.data.data),
      {
        onSuccess: () => Inertia.visit('/')
      }
    );
    return { user };
  }
};
</script>

<style></style>
