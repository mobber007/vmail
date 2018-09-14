<template>
  <div class="content text-center">
    <div class="title m-b-md" style="font-family: Raleway !important">
      LOOKUP
    </div>
    <span v-if="!validateEmail(email)" :style="bindAlert()" role="alert" class="invalid-feedback"><strong>The email format is invalid.</strong></span>
    <span v-if="validateEmail(email)" role="alert" :style="bindSuccess()" class="invalid-feedback"><strong>The email format is valid.</strong></span>
    <div class="form-group row" style="max-width: 400px; min-width: 350px; margin: 0 auto">
      <input  type="email" v-model="email" class="form-control col-9" id="email" placeholder="Insert email here" required>
      <button v-if="!loading" class="col-3 btn btn-info text-light" v-on:click="verify()">GO</button>
      <button v-else="" class="col-3 btn btn-info text-light"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></button>
    </div>
    <div class="modal fade" id="lookup_response" tabindex="-1" role="dialog" aria-labelledby="lookup_response" style="padding-top: 15vh;" backdrop="static" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="lookup_response">Lookup results</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-left">
            <pre>{{response}}</pre>
          </div>
          <div class="modal-footer">
            <button v-if="response.deliverable" type="button" class="btn btn-success" data-dismiss="modal">VALID</button>
            <button v-else="" type="button" class="btn btn-danger" data-dismiss="modal">INVALID</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: () =>
  ({
    email: '',
    response: '',
    error: false,
    servers_online: '',
    loading: false
  }),
  watch: {
    'servers_online': function(){
      if(this.servers_online === 0)
      {
        console.log('No servers_online')
      }
    }
  }
  ,
  methods:
  {
    async verify()
    {
      if(this.validateEmail(this.email))
      {
        this.loading = true
        await axios.get('/api/' + this.email)
        .then( response => {
          this.loading = false
          this.response = response.data.data
          this.servers_online = response.data.data.servers_online
          if(this.response)
          {
          $('#lookup_response').modal({'backdrop': 'static', 'show': true})
          }
          else {
            this.error = true
          }
      })
    }
    else {
    this.error = true
    }
  },
  validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
},
bindAlert()
{
  if(!this.validateEmail(this.email))
  return 'display: block'
  else {
    return 'display: none'
  }
},
bindSuccess()
{
  if(this.validateEmail(this.email))
  return 'display: block; color: green'
  else {
    return 'display: none; color: green'
  }
}
}
}
</script>
<style scoped>
.pre{
  display: block !important;
  font-family: monospace;
  white-space: pre !important;
  text-align: left;
  margin: 1em 0px !important;
}
</style>
