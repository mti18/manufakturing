<template>
    <section>   
      <div class="card card-custom">
        <div class="card-header card-header-tabs-line">
        <div class="card-title">
        <h2 class="card-label">Hutang Piutang</h2>
        </div>
        <div class="card-toolbar">
        <ul class="nav nav-tabs nav-bold nav-tabs-line">
            <li class="nav-item "  >
            <a class="nav-link active" data-bs-toggle="pill" href="#table-hutang"  >
                <span class="nav-icon"><i class="flaticon2-chat-1"></i></span>
                <span class="nav-text">Hutang</span>
            </a>
            </li>
            <li class="nav-item" >
            <a class="nav-link" data-bs-toggle="pill" href="#table-piutang" >
                <span class="nav-icon"><i class="flaticon2-chat-2"></i></span>
                <span class="nav-text">Piutang</span>
            </a>
            </li>
        </ul>
        </div>
        </div>
        <div class="card-body">
        <div class="tab-content">
        <div class="tab-pane fade show active" id="table-hutang" role="tabpanel" aria-labelledby="kt_tab_pane_1_3">
            <Hutang ></Hutang>
        </div>
        <div class="tab-pane fade " id="table-piutang" role="tabpanel" aria-labelledby="kt_tab_pane_2_3" >
            <Piutang></Piutang>
        </div> 
        </div>
        </div>
        </div>
    </section>
  </template>
  
  <script>
   import { ref, h } from "vue";
  import Hutang from "./hutang/Index.vue";
  import Piutang from "./piutang/Index.vue";
  
  export default {
    components: {
      Hutang,
      Piutang,
    },
    data(){

    },
   
    methods: {

    },
    
    mounted(){

    }
  }
  </script>
  
  <style>
  
  </style>