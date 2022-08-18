import AppForm from '../app-components/Form/AppForm';

Vue.component('pdf-polling-log-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                key:  '' ,
                value:  '' ,
                meta:  '' ,
                log:  '' ,
                
            }
        }
    }

});