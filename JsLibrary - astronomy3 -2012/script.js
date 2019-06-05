
// load the mindmap
        $(document).ready(function() {
            // enable the mindmap in the body
            $('body').mindmap();

            // add the data to the mindmap
            var root = $('body').addRootNode('Astronomy', {
                href:'/',
                url:'/',
                onclick:function(node) {
                    $(node.obj.activeNode.content).each(function() {
                        this.hide();
                    });
                }
            });
            $('h2').each(function() {
                var mynode = $('body').addNode(root, $(this).text(), {
                    href:$(this).text().toLowerCase(),
                    onclick:function(node) {
                        $(node.obj.activeNode.content).each(function() {
                            this.hide();
                        });
                        $(node.content).each(function() {
                            this.show();
                        });
                    }
                });
                $(this).hide();
                var parentnode = mynode;
                for (var $el = $(this).next(); $el.length>0; $el = $el.next()) {
                    if ($el[0].tagName=="svg") break;
                    if ($el[0].tagName=="DIV") break;  // simply because the svg node is held in a div in IE
                    if ($el[0].tagName=="H2") break;
                    switch($el[0].tagName) {
                        case 'H3':
                            parentnode = $('body').addNode(mynode, $el.text(), {
                                href:$(this).text().toLowerCase(),
                                onclick:function(node) {
                                    $(node.obj.activeNode.content).each(function() {
                                        this.hide();
                                    });
                                    $(node.content).each(function() {
                                        this.show();
                                    });
                                }
                            });
                            $el.hide();
                        break;
                        default:
                            parentnode.content.push($el);
                            $el.hide();
                        break;
                    }
                }
            });
        
        });   