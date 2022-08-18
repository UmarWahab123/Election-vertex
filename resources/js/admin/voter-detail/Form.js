import AppForm from '../app-components/Form/AppForm';

Vue.component('voter-detail-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                id_card:  '' ,
                serial_no:  '' ,
                family_no:  '' ,
                block_code:  '' ,
                age:  '' ,
                name:  '' ,
                father_name:  '' ,
                address:  '' ,
                cron:  '' ,
                status:  '' ,
                meta:  '' ,
                
            }
        }
    }

});