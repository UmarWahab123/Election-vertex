import AppForm from '../app-components/Form/AppForm';

Vue.component('pdf-polling-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                email:  '' ,
                block_code:  '' ,
                status:  '' ,
                
            }
        }
    }

});