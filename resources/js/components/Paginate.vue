<template>
  <div :id="id">
    <div class="d-flex justify-content-between gap-2 flex-wrap mb-4">
      <div class="d-flex gap-4 align-items-center">
        <label htmlFor="limit" class="form-label">
          Tampilkan
        </label>
        <select2 class="form-control w-75px" v-model="per" placeholder="Per">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select2>
      </div>
      <form @submit.prevent="refetch">
        <input
          type="search"
          class="form-control"
          placeholder="Cari ..."
          v-model="search"
          v-debounce="onSearch"
        />
      </form>
    </div>
    <div class="table-responsive">
      <table class="table table-rounded table-striped border gy-7 gs-7">
        <thead class="bg-gray-200">
          <tr class="fw-bolder fs-6 text-gray-800 border-bottom border-gray-200"
            v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <th v-for="header in headerGroup.headers" :key="header.id" class="py-4">
              <FlexRender :render="header.isPlaceholder ? null : header.column.columnDef.header" :props="header.getContext()" />
            </th>
          </tr>
        </thead>
        <tbody>
          <template v-if="!!data?.data?.length">
            <tr v-for="row in table.getRowModel().rows" :key="`row.${row.original.uuid}`">
              <td v-for="cell in row.getVisibleCells()" :key="`cell.${cell.id}.${cell.row.original.uuid}`" class="py-4">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </td>
            </tr>
          </template>
          <tr v-else>
            <td :colspan="columns.length" class="text-center py-4">
              Data tidak ditemukan
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-between mt-2 mt-md-0 flex-wrap gap-2">
      <div class="text-gray-700 fs-7">
        Menampilkan {{ data?.from }} sampai {{ data?.to }} dari {{ data?.total }} hasil
      </div>
      <ul class="pagination">
        <li class="page-item previous" :class="{ disabled: data?.current_page == 1 || !data }">
          <span @click="page = data?.current_page - 1" class="page-link">
            <i class="previous"></i>
          </span>
        </li>
        <li v-for="item in pagination" :key="item" @click="page = item" class="page-item" :class="{ active: item === page }"
        >
          <span class="page-link cursor-pointer">{{ item }}</span>
        </li>
        <li class="page-item next" :class="{ disabled: data?.current_page == data?.last_page || !data }">
          <span @click="page = data?.current_page + 1" class="page-link cursor-pointer">
            <i class="next"></i>
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { useQuery } from "vue-query";
import { ref } from "vue";
import {
  useVueTable,
  FlexRender,
  getCoreRowModel,
} from "@tanstack/vue-table";
import axios from "@/libs/axios";

export default {
  props: {
    id: {
      type: String,
      required: true
    },
    columns: {
      type: Object,
      required: true,
    },
    url: {
      type: String,
      required: true
    },
    payload: {
      type: Object,
      default: {}
    }
  },
  components: {
    FlexRender
  },
  setup(props) {
    const search = ref("");
    const debouncedSearch = ref("");

    const per = ref(10);
    const page = ref(1);

    const { data = {}, isFetching, refetch } = useQuery([props.url], () => axios.post(props.url, {
      search: search.value, 
      page: page.value,
      per: parseInt(per.value),
      ...props.payload
    }).then(res => res.data), {
      placeholderData: { data: [] },
      cacheTime: 0
    });

    const table = useVueTable({
      get data() {
        return data.value.data
      },
      columns: props.columns,
      getCoreRowModel: getCoreRowModel(),
    });

    return {
      search, debouncedSearch,
      table,
      per, page,
      data,
      isFetching,
      refetch
    }
  },
  methods: {
    onSearch() {
      this.debouncedSearch = this.search;
    },
  },
  watch: {
    page() {
      this.refetch();
    },
    per() {
      this.refetch();
    },
    debouncedSearch() {
      this.refetch();
    },
    isFetching(val) {
      if ( val && !document.querySelector("#" + this.id).querySelector(".blockui-overlay")) KTApp.block("#" + this.id);
      else KTApp.unblock("#" + this.id);
    },
  },
  computed: {
    pagination() {
      let limit = this.data?.last_page <= this.page + 1 ? 5 : 2;
      return Array.from({ length: this.data?.last_page }, (_, i) => i + 1).filter(
        (i) =>
          i >= (this.page < 3 ? 3 : this.page) - limit && i <= (this.page < 3 ? 3 : this.page) + limit
      )
    }
  },
  mounted() {
    KTApp.block("#" + this.id);
  }
}
</script>

<style>

</style>