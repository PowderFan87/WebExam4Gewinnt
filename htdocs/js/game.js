/**
 * game.js core game application for Frontend
 * 
 */

Game = {
    'data': null,
    'gid': null,
    'root': '',
    'intval': null,
    'init': function(root,gid) {
        this.gid = gid;
        this.root = root;
        
        $('.field').on('click.Game', function(){
            if(!($('#gamegrid').hasClass('hasturn'))) {
                return;
            }
            
            var id = ($(this).parent().attr('id')).replace('r','');
            var turn = false;
            
            for(var i in Game.data.grid[id]) {
                if(Game.data.grid[id][i] === 0) {
                    turn = true;
                    break;
                }
            }
            
            if(!turn) {
                alert('ungültiger zug');
            } else {
                Game.sendRequest(
                    '/XGame/Move',
                    {
                        'uid': Game.gid,
                        'move': [id,i]
                    },
                    function(d){
                        if(d.msg === 'error') {
                            // error ausgabe
                            return;
                        }
                        
                        if(d.msg === 'winner') {
                            // do winner logik
                            return;
                        }
                        
                        $(d.data.last).removeClass('coin0').addClass('coin'+this.data.play);
                        this.data = d.data;
                        $('#gamegrid').toggleClass('hasturn');
                        
                        this.intval = window.setInterval(function(){Game.checkUpdate();}, 2000);
                    },
                    'json'
                );
            }
        });
        
        this.sendRequest(
            '/XGame/Data',
            {
                'uid': this.gid
            },
            function(d){
                this.data = d;
                
                this.updateGrid();
                
                if(!($('#gamegrid').hasClass('hasturn'))) {
                    this.intval = window.setInterval(function(){Game.checkUpdate();}, 2000);
                }
            },
            'json'
        );
    },
    'updateGrid': function() {
        for(y in this.data.grid) {
            for(x in this.data.grid[y]) {
                $('#f'+y+''+x).addClass('coin'+this.data.grid[y][x]);
            }
        }
    },
    'checkUpdate': function() {
        this.sendRequest(
            '/XGame/Update',
            {
                'uid': this.gid
            },
            function(d){
                if(d.msg === 'none') {
                    return;
                }
                
                if(d.msg === 'winner') {
                    // do winner logik
                    return;
                }
                
                window.clearInterval(Game.intval);
                
                this.data = d.data;
                this.updateGrid();
                
                $('#gamegrid').toggleClass('hasturn');
            },
            'json'
        );
    },
    'displayWin': function() {
        // winner logik
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