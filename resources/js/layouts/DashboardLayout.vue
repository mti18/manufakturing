<template>
  <main
    id="kt_main"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height: 0px; --kt-toolbar-height-tablet-and-mobile: 0px"
  >
    <div class="d-flex flex-column flex-root">
      <div class="page d-flex flex-row flex-column-fluid">
        <aside
          id="kt_aside"
          class="aside aside-dark aside-hoverable"
          data-kt-drawer="true"
          data-kt-drawer-name="aside"
          data-kt-drawer-activate="{default: true, lg: false}"
          data-kt-drawer-overlay="true"
          data-kt-drawer-width="{default:'200px', '300px': '250px'}"
          data-kt-drawer-direction="start"
          data-kt-drawer-toggle="#kt_aside_mobile_toggle"
        >
          <div class="aside-logo flex-column-auto" id="kt_aside_logo">
            <Link href="/">
              <img
                alt="Logo"
                :src="$page.props.setting.logo_dark_url"
                class="h-25px logo"
              />
            </Link>
            <div
              id="kt_aside_toggle"
              class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
              data-kt-toggle="true"
              data-kt-toggle-state="active"
              data-kt-toggle-target="body"
              data-kt-toggle-name="aside-minimize"
            >
              <span class="svg-icon svg-icon-1 rotate-180">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                >
                  <path
                    opacity="0.5"
                    d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                    fill="black"
                  ></path>
                  <path
                    d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                    fill="black"
                  ></path>
                </svg>
              </span>
            </div>
          </div>
          <div class="aside-menu flex-column-fluid">
            <div
              class="hover-scroll-overlay-y my-5 my-lg-5"
              id="kt_aside_menu_wrapper"
              data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}"
              data-kt-scroll-height="auto"
              data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
              data-kt-scroll-wrappers="#kt_aside_menu"
              data-kt-scroll-offset="0"
              style="height: 216px"
            >
              <div
                class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu"
                data-kt-menu="true"
                data-kt-menu-expand="false"
              >
                <template v-for="menu in menus">
                  <template v-if="!!menu.children.length">
                    <menu-accordion
                      :menu="menu"
                      :key="`accordion-${menu.uuid}`"
                    />
                  </template>
                  <template v-else>
                    <menu-item :menu="menu" :key="`item-${menu.uuid}`" />
                  </template>
                </template>
              </div>
            </div>
          </div>
        </aside>

        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
          <header class="header align-items-stretch" id="kt_header">
            <div class="container-fluid d-flex align-items-stretch">
              <div
                class="d-flex align-items-center d-lg-none ms-n2 me-2"
                title="Show aside menu"
              >
                <div
                  class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                  id="kt_aside_mobile_toggle"
                >
                  <i class="las la-bars" style="font-size: 2rem"></i>
                </div>
              </div>

              <div class="d-flex align-items-center flex-grow-0 ms-4">
                <Link class="d-lg-none">
                  <img alt="Logo" class="h-30px" />
                </Link>
              </div>

              <div
                class="d-flex align-items-stretch justify-content-between flex-grow-1"
              >
                <div
                  class="d-flex align-items-stretch px-4 px-lg-0 ms-4 ms-lg-0"
                  id="kt_header_nav"
                >
                  <ol
                    class="breadcrumb breadcrumb-line text-muted fs-6 fw-semibold"
                  >
                    <li
                      class="breadcrumb-item pe-3"
                      v-for="(breadcrumb, i) in $page.props.breadcrumb"
                      :key="breadcrumb"
                    >
                      <a
                        href=""
                        v-if="i !== $page.props.breadcrumb.length - 1"
                        >{{ breadcrumb }}</a
                      >
                      <span v-else class="text-muted">{{ breadcrumb }}</span>
                    </li>
                  </ol>
                </div>

                <div class="d-flex align-items-stretch flex-shrink-0">
                  <div
                    class="d-flex align-items-center ms-1 ms-lg-3"
                    id="kt_header_user_menu_toggle"
                  >
                    <div
                      class="cursor-pointer symbol symbol-30px symbol-md-40px show menu-dropdown"
                      data-kt-menu-trigger="click"
                      data-kt-menu-attach="parent"
                      data-kt-menu-placement="bottom-end"
                    >
                      <img :src="asset(user?.avatar)" alt="user" />
                    </div>
                    <div
                      class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                      data-kt-menu="true"
                      data-popper-placement="bottom-end"
                      style="
                        position: fixed;
                        margin: 0;
                        transform: translate(-30px, 65px);
                      "
                    >
                      <div class="menu-item px-3">
                        <div
                          class="menu-content d-flex align-items-center px-3"
                        >
                          <div class="symbol symbol-50px me-5">
                            <img alt="User" :src="asset(user?.avatar)" />
                          </div>
                          <div class="d-flex flex-column">
                            <div
                              class="fw-bolder d-flex align-items-center fs-5"
                            >
                              {{ user && user.name }}
                              <span
                                class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2"
                              >
                              </span>
                            </div>
                            <span
                              class="fw-bold text-muted text-hover-primary fs-7"
                            >
                              {{ user && user.email }}
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="separator my-2"></div>
                      <div class="menu-item px-5">
                        <Link class="menu-link px-5"> Akun Saya </Link>
                      </div>
                      <div class="menu-item px-5">
                        <!-- <Link class="menu-link px-5" :href="route('updatepw')">
                          Ganti Password
                        </Link> -->
                      </div>
                      <div class="menu-item px-5">
                        <span @click="logout" class="menu-link px-5 text-danger"
                          >Logout</span
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </header>

          <div
            class="content d-flex flex-column flex-column-fluid"
            id="kt_content"
          >
            <div
              class="post d-flex flex-column-fluid"
              id="kt_post mt-6 mt-lg-0"
            >
              <div
                v-if="statusAccess === 'success'"
                id="kt_content_container"
                class="container-xxl"
              >
                <slot />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { useQuery, useMutation } from "vue-query";
import axios from "@/libs/axios";
import { asset } from "@/libs/utils";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";

export default {
  setup() {
    const { data: user = {} } = useQuery(
      ["user"],
      () => axios.get("/auth/user").then((res) => res.data.data),
      {
        onError: () => Inertia.visit("/login"),
      }
    );
    const { data: menus = [] } = useQuery(
      ["dashboard", "menus"],
      () => axios.get("/dashboard/menu").then((res) => res.data),
      {
        onSuccess: () => {
          const script = document.createElement("script");
          script.src = asset("assets/js/loader.bundle.js");
          script.async = true;

          document.body.appendChild(script);
        },
      }
    );

    const { mutate: logout } = useMutation(() => axios.post("/auth/logout"), {
      onMutate: () => KTApp.block("body"),
      onSettled: () => window.location.reload(),
    });

    const {
      data: hasAccess,
      status: statusAccess,
      refetch,
    } = useQuery(
      ["has-access"],
      () =>
        axios
          .post("/dashboard/menu/has-access", {
            menu_uuid: usePage().props.value.menu.uuid,
          })
          .then((res) => res.data.status),
      {
        cacheTime: 0,
        onError: (error) => {
          if (error.response?.status === 403) {
            toastr.error("Anda tidak memiliki akses ke halaman ini");
            Inertia.visit("/");
          }
        },
      }
    );
    return { user, menus, logout, hasAccess, statusAccess, refetch };
  },
  watch: {
    "$page.props.menu.uuid": {
      handler: function (val, oldVal) {
        this.refetch();
      },
      deep: true,
    },
  },
};
</script>

<style></style>
