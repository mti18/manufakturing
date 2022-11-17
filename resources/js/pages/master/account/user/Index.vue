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
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="nm_account">Tambahkan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-8">
                  <label for="nm_account" class="form-label required">Tambah</label>
                  <input type="text" name="nm_account" id="nm_account" placeholder="Add"
                    class="form-control" required autoComplete="off"  v-model="formRequest.nm_account" />
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button  type="submit" class="btn btn-primary" @click="send()" >Tambah</button>
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
        formRequest: {},
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
          editNode: { state: false, fn: null, appearOnHover: false },
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
      app.axios.post('account/send', app.formRequest).then((res) => {
        toastr.success(`sukses data`, 'success');
                    $('#exampleModal').modal('hide');
                    KTApp.block("#nm_account",{
						overlayColor:"#000000",
						type:"v2",
						state:"primary",
						message:"Processing...",
						opacity: 0.2
        
					});
          app.getData();
          
      }).catch((err) => {
        toastr.error('sesuatu error terjadi', 'error');
      })
    },
  
    // send(){
    //   var app = this;
    //   app.axios.get('account/getData', app.formRequest).then((res) => {
    //     app.treeDisplayData = res.data.data
    //   }).catch((err) => {
    //     toastr.error('sesuatu error terjadi', 'error');
    //   })
    // },
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
       

        // const nodePath = this.$refs["my-tree"].findNodePath(node.id);
        // const parentNodeId = nodePath.slice(-2, -1)[0];
        // if (parentNodeId === undefined) {
        //   // 'root' node
        //   const nodeIndex =
        //     this.$refs["my-tree"].nodes.findIndex((x) => x.id !== node.id) - 1;
        //   this.$refs["my-tree"].nodes.splice(nodeIndex, 1);
        // } else {
        //   // child node
        //   const parentNode = this.$refs["my-tree"].findNode(parentNodeId);
        //   const nodeIndex =
        //     parentNode.nodes.findIndex((x) => x.id !== node.id) - 1;
        //   parentNode.nodes.splice(nodeIndex, 1);
        // }
        // console.log("example: remove node", node.id);
      },
      addNodeFunction: function (node) {
        this.formRequest.parent_id = node.id
        $('#exampleModal').modal('show')
        
      //   const newNode = useMutation((data) => axios.post(selected ? `/account/${selected}/update` : '/account/store', data).then(res => res.data), {
      //   onMutate: () => {
      //     KTApp.block("#index-account");
      //   },
      //   onError: (error) => {
      //     toastr.error(error.response.data.message);
      //   },
      //   onSettled: () => {
      //     KTApp.unblock("#index-account");
      //   }
      // });
      //   console.log("example: add node",newNode);
      //   if (node.nodes === undefined) {
      //     node.nodes = [newNode];
      //   } else {
      //     node.nodes.push(newNode);
      //   }
      // },
    },
  },

    setup() {
    const selected = ref();
    const openForm = ref(false);
    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) => h('div', { class: 'd-flex gap-2' }, [
            h('button', { class: 'btn btn-sm btn-icon btn-warning', onClick: () => {
              selected.value = cell.getValue();
              openForm.value = true;
            }}, h('i', { class: 'la la-pencil fs-2' })), 
            h('button', { class: 'btn btn-sm btn-icon btn-danger' }, h('i', { class: 'la la-trash fs-2' }))
          ]),
      }),
    ]

    return {
    
      selected,
      openForm,
      columns,
    }
  }
  };
  </script>