import "./bootstrap";

import { Inertia } from "@inertiajs/inertia";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { QueryClient  } from "vue-query";

import VueAxios from "vue-axios";
import axios from "./libs/axios";

import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

import { VueQueryPlugin } from "vue-query";
import { Money3Directive } from "v-money3";
import VueMask from "@devindex/vue-mask";
import { vue3Debounce } from 'vue-debounce'
import {
  plugin as FormkitPlugin,
  defaultConfig as FormkitConfig,
} from "@formkit/vue";
import { Link } from "@inertiajs/inertia-vue3";
import VOtpInput from "vue3-otp-input";

import DashboardLayout from "./layouts/DashboardLayout.vue";
import AuthLayout from "./layouts/AuthLayout.vue";

// Dashboard Components
import MenuItem from "./layouts/components/MenuItem.vue";
import MenuAccordion from "./layouts/components/MenuAccordion.vue";
import Paginate from "./components/Paginate.vue";
import Select2 from "./components/Select2.vue";
import Datepicker from "./components/Datepicker.vue";
import FileUpload from "@/components/FileUpload.vue";
import { CKEditor } from "@ckeditor/ckeditor5-vue";
import "ckeditor5-custom-build/build/ckeditor";

const appName =
  window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      refetchOnmount: false,
      retry: false,
      staleTime: 1000 * 60 * 60 * 1,
      networkMode: "always",
    },
    mutations: {
      networkMode: "always",
    },
  },
});

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => {
    const page = resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob("./pages/**/*.vue")
    );
    page.then((module) => {
      if (name.startsWith("auth")) {
        module.default.layout = AuthLayout;
      } else {
        module.default.layout = DashboardLayout;
      }
    });
    return page;
  },
  setup({ el, app, props, plugin }) {
    const vApp = createApp({ render: () => h(app, props) })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(CKEditor)
      .use(ToastPlugin)
      .use(VueQueryPlugin, {
        queryClient,
      })
      .use(FormkitPlugin, FormkitConfig)
      .use(VueMask)
      .use(VueAxios, axios)
      .directive("money", Money3Directive)
      .directive('debounce', vue3Debounce({ lock: true }))
      .component("file-upload", FileUpload)
      .component("Link", Link)
      .component('v-otp-input', VOtpInput)
      .component("menu-accordion", MenuAccordion)
      .component("menu-item", MenuItem)
      .component('mti-paginate', Paginate)
      .component('select2', Select2)
      .component('datepicker', Datepicker);

    vApp.config.globalProperties.$route = route;
    vApp.mixin({
      methods: {
        asset: function (uri) {
          return (
            import.meta.env.VITE_URL + "/" + uri?.split("/").filter(Boolean).join("/")
          );
        }
      }
    })

    vApp.mount(el);
    return vApp;
  },
});

InertiaProgress.init({ color: "#2563eb" });

Inertia.on("navigate", (event) => {
  const queryClient = new QueryClient();
  const user = queryClient.getQueryData(["user"]);
  const auth = event.detail.page.props.auth;

  if (auth) {
    if (!localStorage.getItem("auth-token")) {
      Inertia.visit("/login");
    }
  } else {
    if (user && localStorage.getItem("auth-token")) {
      Inertia.visit("/");
    }
  }
});

window.findDeep = (arr = [], value, key = 'uuid', onFound) => {
  for (let i = 0; i < arr.length; i++) {
    if (arr[i][key] === value) {
      return arr[i];
    } else if (arr[i].children.length) {
      const item = findDeep(arr[i].children, value);
      if (item) {
        return item;
      }
    }
  }
};

window.getPropertyDeep = (arr = [], prop, onFound) => {
  for (let i = 0; i < arr.length; i++) {
    if (arr[i].hasOwnProperty(prop)) {
      onFound(arr[i][prop]);
    }
    if (arr[i].children?.length) {
      getPropertyDeep(arr[i].children, prop, onFound);
    }
  }
}

Number.prototype.rupiah = function(useComma = false) {
  if (this.toString().substr(0, 1) == '-') {
    if (useComma) {
      var nml = this.toString().split(',');
      var val = this.toString()
        .replace(/-/, '')
        .replace(/\D/g, '');
      if (nml[1] != undefined && nml[0][1] != undefined) {
        var spl = val.replace(/,/, '').replace(/\D/g, '');
        var str =
          val
            .replace(/,/, '')
            .replace(/\D/g, '')
            .substr(0, spl.length - 2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
          ',' +
          spl.substr(-2);
      } else if (nml[0][1] == undefined && val.includes(',')) {
        if (
          parseInt(
            this.toString()
              .replace(/\./g, '')
              .replace(/\,/g, '.')
          ) > 0
        ) {
          var str = this.toString().replace(/,/, '');
        } else {
          var str = nml[0] == undefined ? '0,' + nml[1] : parseInt(this.toString().replace(/\,/g, ''));
        }
      } else {
        var str = val
          .toString()
          .replace(/\D/g, '')
          .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }

      str = '-' + str;
    } else {
      str = this.toString()
        .substr(1)
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      str = '-' + str;
    }
  } else {
    if (useComma) {
      var nml = this.toString().split(',');
      if (nml[1] != undefined && nml[0][1] != undefined) {
        var spl = this.toString()
          .replace(/,/, '')
          .replace(/\D/g, '');
        var str =
          this.toString()
            .replace(/,/, '')
            .replace(/\D/g, '')
            .substr(0, spl.length - 2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
          ',' +
          spl.substr(-2);
      } else if (nml[0][1] == undefined && this.toString().includes(',')) {
        if (
          parseInt(
            this.toString()
              .replace(/\./g, '')
              .replace(/\,/g, '.')
          ) > 0
        ) {
          var str = this.toString().replace(/,/, '');
        } else {
          var str = nml[0] == undefined ? '0,' + nml[1] : parseInt(this.toString().replace(/\,/g, ''));
        }
      } else {
        var str = this.toString()
          .replace(/\D/g, '')
          .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }
    } else {
      var str = this.toString()
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
  }

  return str;
};

String.prototype.rupiah = function(useComma = false) {
  if (this.toString().substr(0, 1) == '-') {
    if (useComma) {
      var nml = this.toString().split(',');
      var val = this.toString()
        .replace(/-/, '')
        .replace(/\D/g, '');
      if (nml[1] != undefined && nml[0][1] != undefined) {
        var spl = val.replace(/,/, '').replace(/\D/g, '');
        var str =
          val
            .replace(/,/, '')
            .replace(/\D/g, '')
            .substr(0, spl.length - 2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
          ',' +
          spl.substr(-2);
      } else if (nml[0][1] == undefined && val.includes(',')) {
        if (
          parseInt(
            this.toString()
              .replace(/\./g, '')
              .replace(/\,/g, '.')
          ) > 0
        ) {
          var str = this.toString().replace(/,/, '');
        } else {
          var str = nml[0] == undefined ? '0,' + nml[1] : '0,00';
        }
      } else {
        var str = val
          .toString()
          .replace(/\D/g, '')
          .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }

      str = '-' + str;
    } else {
      str = this.toString()
        .substr(1)
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      str = '-' + str;
    }
  } else {
    if (useComma) {
      var nml = this.toString().split(',');
      if (nml[1] != undefined && nml[0][1] != undefined) {
        var spl = this.toString()
          .replace(/,/, '')
          .replace(/\D/g, '');
        var str =
          this.toString()
            .replace(/,/, '')
            .replace(/\D/g, '')
            .substr(0, spl.length - 2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
          ',' +
          spl.substr(-2);
      } else if (nml[0][1] == undefined && this.toString().includes(',')) {
        if (
          parseInt(
            this.toString()
              .replace(/\./g, '')
              .replace(/\,/g, '.')
          ) > 0
        ) {
          var str = this.toString().replace(/,/, '');
        } else {
          var str = nml[0] == undefined ? '0,' + nml[1] : '0,00';
        }
      } else {
        var str = this.toString()
          .replace(/\D/g, '')
          .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }
    } else {
      var str = this.toString()
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
  }

  return str;
};

String.prototype.ucfirst = function() {
  return `${this[0].toUpperCase()}${this.slice(1)}`;
};

String.prototype.ucword = function() {
  var str = this;
  str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
    return letter.toUpperCase();
  });
  return str;
};

String.prototype.indo = function() {
  var months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  var date = this.split('-');
  var day = date[2];
  var month = parseInt(date[1]);
  var year = date[0];
  return day + ' ' + months[month] + ' ' + year;
};

String.prototype.tgl_indo = function(time = false) {
  var months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  var d = this.split(' ');
  var date = d[0].split('-');
  var day = date[2];
  var month = parseInt(date[1]);
  var year = date[0];
  if (time) {
    return day + ' ' + months[month] + ' ' + year + ' ' + d[1];
  } else {
    return day + ' ' + months[month] + ' ' + year;
  }
};

String.prototype.zxc = function() {
  var r = '';
  var b = btoa(this);
  for (var i = 0; i < b.length; i++) {
    r += b.charCodeAt(i).toString(16);
  }
  return btoa(r);
};
