
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

Vue.component('chart',{

    data() {
        return {
            id:0,
            chart_version_id:'',
            country:'',
            is_accountable:'',
            code:'',
            name:'',
            level: '',
            type:'',
            sub_type:'',
            list: [
              //     {
              //     id:0,
              //     chart_version_id:'',
              //     chart_version_name:'',
              //     country:0,
              //     is_accountable:'',
              //     code:'',
              //     name:'',
              //     level:'',
              //     type:'',
              //     sub_type:''

              // }
            ],

        }
    },

    methods: {


        //Takes Json and uploads it into Sales INvoice API for inserting. Since this is a new, it should directly insert without checking.
        //For updates code will be different and should use the ID's palced int he Json.
        onSave: function(json)
        {
            var app=this;
            var api=null;

            $.ajax({
                url: '/store_chart/',
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                type: 'post',
                data:json,
                dataType: 'json',
                async: false,
                success: function(data)
                {
                    if (data=='ok') {
                        app.id=0;
                        app.chart_version_id=null;
                        app.country=null;
                        app.is_accountable=null;
                        app.code=null;
                        app.name=null;
                        app.level=null;
                        app.type=null;
                        app.sub_type=null;
                        app.init();
                    }
                    else {
                        alert('Something Went Wrong...')
                    }


                },
                error: function(xhr, status, error)
                {
                    console.log(error);
                }
            });
        },
        onEdit: function(data)
        {
            var app=this;
            app.id=data.id;
            app.chart_version_id=data.chart_version_id;
            app.country=data.country;
            app.is_accountable=data.is_accountable;
            app.code=data.code;
            app.name=data.name;
            app.level=data.level;
            app.type=data.type;
            app.sub_type=data.sub_type;
        },
        init(){
            var app=this;
            $.ajax({
                url: '/get_chart/' ,
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(data)
                {
                    app.list=[];
                    for(let i = 0; i < data.length; i++)
                    {
                        app.list.push({name:data[i]['name'],id:data[i]['id']});
                    }

                },
                error: function(xhr, status, error)
                {
                    console.log(status);
                }
            });
        }
    },

    mounted: function mounted() {

        this.init()






    }
});