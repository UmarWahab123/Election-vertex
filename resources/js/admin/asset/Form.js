import AppForm from '../app-components/Form/AppForm';

Vue.component('asset-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                tag_id:  '' ,
                url:  '' ,
                title:  '' ,
                content:  '' ,
                status:  '' ,
                
            }
        }
    }

});