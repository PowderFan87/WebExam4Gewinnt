/**
 * game.js core game application for Frontend
 * 
 */

Game = {
    'data': null,
    'gid': null,
    'root': '',
    'init': function(root,gid) {
        this.gid = gid;
        this.root = root;
        
        this.sendRequest(
            '/XGame/Data',
            {
                'uid': this.gid
            },
            function(d){
                this.data = d;
                
                for(i in this.data.grid) {
                    for(c in this.data.grid[i]) {
                        $('#f'+i+''+c).addClass('coin'+this.data.grid[i][c]);
                    }
                }
            },
            'json'
        );
    },
    'sendRequest': function(u, d, s, r) {
        $.ajax({
            type: "POST",
            url: this.root + u,
            data: d,
            success: s,
            dataType: r,
            context: this,
            statusCode: {
                401: function() {
                    this.log('Unauthorized action');
                },
                404: function() {
                    this.log('URL not found');
                }
            }
        });
    },
    'log': function(l) {
        if(window.console) {
            console.log(l);
        }
    }
};