var request = require('supertest')

describe('POST /uploads', function(){
    it('respond with json', function(done){
        request('http://127.0.0.1:8765')
            .post('/uploads/upload')
            .attach('files', 'tests/TestCase/Controller/_files/small.jpg')
            .set('Accept', 'application/json')
            .expect('Content-Type', /json/)
            .expect(200, 
                    {files: [
                        {
                            name: 'small.jpg',
                            size: 79551,
                            type: 'image/jpeg',
                            url: 'http://127.0.0.1:8765/files/small.jpg',
                            thumbnailUrl: 'http://127.0.0.1:8765/files/thumbnail/small.jpg',
                            deleteUrl: 'http://127.0.0.1:8765/index.php?file=small.jpg',
                            deleteType: 'DELETE'
                        }
                    ]}        
            , done);
    });
});

