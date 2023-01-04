<template>
     <section>
      <Form v-if="openForm" :selected="selected" />
      <div class="card">
        <div class="card-header">
          <div class="card-title w-100">
            <h1>Account</h1>
            <button v-if="!openForm" type="button" class="btn btn-primary btn-sm ms-auto" @click="openForm = true ">
              <i class="las la-plus"></i>
              Account Baru
            </button>
          </div>
        </div> 
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" > 
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id=""> {{(formRequest.uuid == undefined) ? `Tambah`: 'Edit'}}  Account </h1>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" >
                  
                </button>
              </div>
              <div class="modal-body">
                <div>
                  <div class="row">
                    <div class="col-6">
                      <div class="mb-8">
                        <label for="nm_account" class="form-label required"> Account : </label>
                        <input type="text" name="nm_account" id="nm_account" placeholder="Account"
                        class="form-control" required autoComplete="off" v-model="formRequest.nm_account" />
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-8">
                        <label  for="account_type" class="form-label required">Account Type</label>
                    <select2 class="form-control" name="account_type"
                    placeholder="Pilih" v-model="formRequest.account_type">
                    <option value="rill">Rill</option>
                    <option value="nominal">Nominal</option>
                  </select2>
                </div>
              </div>
            </div>
              <div class="row">
                <div class="col-6">
                  <div class="mb-6">
                    <label  for="Type" class="form-label required">Type</label>
                    <select2 class="form-control" name="type"
                    placeholder="Pilih" v-model="formRequest.type">
                    <option value="debit">Debit</option>
                            <option value="kredit">Kredit</option>
                      </select2>
                    </div>
                  </div>
                      <div class="col-6">
                        <div class="mb-6">
                          <label  for="account_header" class="form-label required">Account Header</label>
                          <select2 class="form-control" name="account_header"
                          placeholder="Pilih" v-model="formRequest.account_header">
                          <option value="pajak">Pajak</option>
                          <option value="bank">Bank</option>
                          <option value="hutang">Hutang</option>
                          <option value="piutang">Piutang</option>
                        </select2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button v-if="type == 'create'" type="submit" class="btn btn-primary" @click="send()" >Tambah</button>
                <button v-else type="submit" class="btn btn-primary" @click="send()" >Update</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div>
            <Tree
              id="my-tree-id"
              ref="my-tree"
              :custom-options="myCustomOptions"
              :custom-styles="myCustomStyles"
              :nodes="treeDisplayData"
              @onClick="nodeWasClicked">
            
          </Tree>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  import Tree from "vuejs-tree";
  import { useMutation } from "vue-query";
  import { ref, h } from "vue";
  import { createColumnHelper } from "@tanstack/vue-table";
  const columnHelper = createColumnHelper();
  import Form from "./Form.vue";
  
  export default {
    name: "TreeExample",
    components: {
      Tree,
      Form
    },
    data: function () {
      return {
        treeDisplayData: [],
        formRequest: {

        },
        type: 'create'
      };
    },
    computed: {
      myCustomStyles() {
        return {
          tree: {
            style: {
              height: "auto",
              maxHeight: "300px",
              overflowY: "visible",
              display: "inline-block",
              textAlign: "left",
            },
          },
          row: {
            style: {
              width: "500px",
              cursor: "pointer",
            },
            child: {
              class: "",
              style: {
                height: "35px",
              },
              active: {
                style: {
                  height: "35px",
                },
              },
            },
          },
          rowIndent: {
            paddingLeft: "20px",
          },
          text: {
            // class: "" // uncomment this line to overwrite the 'capitalize' class, or add a custom class
            style: {},
            active: {
              style: {
                "font-weight": "bold",
                color: "#2ECC71",
              },
            },
          },
        };
      },
      myCustomOptions() {
        return {
          treeEvents: {
            expanded: {
              state: false,
            },
            collapsed: {
              state: false,
            },
            selected: {
              state: true,
              fn: this.mySelectedFunction,
            },
            checked: {
              state: true,
              fn: this.myCheckedFunction,
            },
          },
          events: {
            expanded: {
              state: true,
            },
            selected: {
              state: true,
            },
            editableName: {
              state: true,
              calledEvent: "expanded",
            },
          },
          addNode: {
            state: true,
            fn: this.addNodeFunction,
            appearOnHover: false,
          },
          editNode: { 
            state: true,
            fn: this.editNodeFunction,
            appearOnHover: false },
          deleteNode: {
            state: true,
            fn: this.deleteNodeFunction,
            appearOnHover: true,
          },
          showTags: true,
        };
      },
    },
    mounted() {
      this.$refs["my-tree"].expandNode(1);
      this.getData()
      
    },
    methods: {

      getData(){
      var app = this;
      app.axios.get('account/getdata').then((res) => {
        app.treeDisplayData = res.data.data
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
    send(){
      var app = this;
                var data = 'account/send';
                var type = 'create';
                if(app.formRequest.uuid != undefined){
                    data = `account/${app.formRequest.uuid}/update`;
                    type = 'update';
                }
                app.axios.post(data, app.formRequest).then((res) => {
                    toastr.success(`sukses ${type} data`, 'success');
                    app.getData();
                    $('#exampleModal').modal('hide');
                }).catch((err) => {
                    toastr.error('sesuatu error terjadi', 'error');
                });

      
    },
   
      myCheckedFunction: function (nodeId, state) {
        console.log(`is ${nodeId} checked ? ${state}`);
        console.log(this.$refs["my-tree"].getCheckedNodes("id"));
        console.log(this.$refs["my-tree"].getCheckedNodes("text"));
      },
      mySelectedFunction: function (nodeId, state) {
        console.log(`is ${nodeId} selected ? ${state}`);
        console.log(this.$refs["my-tree"].getSelectedNode());
      },
      deleteNodeFunction: function (node) {
        
        var app = this;

          Swal.fire({
              title: 'Apakah anda yakin?',
              text: 'Data yang dihapus tidak dapat dikembalikan',
              showCancelButton: true,
              icon: 'warning',
              iconHtml: '?',
              confirmButtonText: 'Ya',
              showLoaderOnConfirm: true,
              preConfirm: () => {
                return app.axios.delete( `account/${node.uuid}/destroy`).then((res) => {                
                            return res.data.data;
                        }).catch((error) => {
                            Swal.showValidationMessage(error.response.data.message)
                        });
                    },
                    
              allowOutsideClick: () => false,
          }).then((result) => {
              if (result.isConfirmed) {
                  Swal.fire({
                      icon: 'success',
                      title: result.value,
                  }).then((v) => {
                      app.treeDisplayData = null;
                      app.getData();


                  });
              }
          });
       

     
      },
      addNodeFunction: function (node) {
        this.formRequest = {
         
        };
        // this.formRequest.parent_id = node.id
        this.type = 'create';
        $('#exampleModal').modal('show')
    },
    editNodeFunction: function (node){
      var app = this;
      app.axios.get( `account/${node.uuid}/edit`).then((res) => {
        app.formRequest =  res.data
        this.type = 'update';
        $('#exampleModal').modal('show')
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
     
      
      
      
    }
  },
  setup() {
      const selected = ref();
      const openForm = ref(false);
      
     
     
      return {
    
        selected,
        openForm,
    
      }
    },
  };

 
  </script>