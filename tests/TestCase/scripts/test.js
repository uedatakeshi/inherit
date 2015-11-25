var request = require('supertest')

describe('POST /uploads', function(){
    it('respond with json', function(done){
        request('http://127.0.0.1:8765')
            .post('/uploads/upload')
            .attach('files', 'tests/TestCase/Controller/_files/kojin_sou.sql')
            .set('Accept', 'application/json')
            //.expect('Content-Type', /json/)
            .expect(function(res) {
                console.log(res.text);
                var obj = JSON.parse(res.text);
                res.body.type = obj.files[0].type;
                res.body.name = obj.files[0].name;
            })
            .expect(200, {
                type: 'image/jpeg',
                name: 'kojin_sou.sql'
            }, done);
    });
});

