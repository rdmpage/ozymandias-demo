//----------------------------------------------------------------------------------------
// http://stackoverflow.com/questions/894860/set-a-default-parameter-value-for-a-javascript-function
function Node(label)
{
	this.ancestor = null;
	this.child = null;
	this.sibling = null;
	this.label = typeof label !== 'undefined' ? label : '';
	this.data = {};
	
}

//----------------------------------------------------------------------------------------
Node.prototype.IsLeaf = function() 
{
	return (!this.child);
}

//----------------------------------------------------------------------------------------
Node.prototype.GetRightMostSibling = function() 
{
	var p = this;
	while (p.sibling)
	{
		p = p.sibling;
	}
	return p;
}

//----------------------------------------------------------------------------------------
Node.prototype.GetDegree = function() 
{
	var degree = 0;
	var p = this;
	if (p.child) {
		degree++;
		
		p = p.child;
		while (p.sibling)
		{
			degree++;
			p = p.sibling;
		}
	}
	return degree;
}

//----------------------------------------------------------------------------------------
function Tree()
{
	this.root = null;
	this.num_leaves = 0;
	this.num_nodes = 0;
	this.label_to_node_map = {};
	this.nodes = [];
	this.curnode = null;
	this.line = [];
	this.html = [];
	this.rooted = true;
}

//----------------------------------------------------------------------------------------
Tree.prototype.NewNode = function(label)
{
	var node = new Node(label);
	node.id = this.num_nodes++;
	this.nodes[node.id] = node;
	
	if (typeof label !== undefined)
	{
		this.label_to_node_map[label] = node.id;
	}
	
	return node;
}

//----------------------------------------------------------------------------------------
Tree.prototype.AddNode = function(ancestor_label, label)
{
	var node = null;
	
	var node_id = this.label_to_node_map[label];
	if (typeof(node_id) !== 'undefined') {
		node = this.nodes[node_id];
	} else {
		node = this.NewNode(label);
	}
		
	var ancestor = null;
	
	if (ancestor_label) {	
		var ancestor_id = this.label_to_node_map[ancestor_label];
		console.log('ancestor ' + ancestor_label + '=' + ancestor_id);
		if (typeof(ancestor_id) !== 'undefined') {
			ancestor = this.nodes[ancestor_id];
		} else {
			ancestor = this.NewNode(ancestor_label);
		}
		
		console.log('ancestor ' + ancestor_label + '=' + ancestor.id);
	}
	
	if (ancestor) {	
		node.ancestor = ancestor;
		if (node.ancestor.child) {
			node.ancestor.child.GetRightMostSibling().sibling = node;
		} else {
			node.ancestor.child = node;
		}	
		console.log("child=" + this.label_to_node_map[node.ancestor.child.label]);
	} else {
		console.log('null');
		this.root = node;
	}
	
	for (var i in this.nodes)
	{
		//console.log(this.nodes[i].id);
	}

}

//--------------------------------------------------------------------------------------------------
Tree.prototype.WriteHtml = function(node_identifier)
{
	var html = '<div class="tree">';
	html += '<ul>';
	
	var stack = [];
	this.curnode = this.root;
	
	
	while (this.curnode)
	{
		html += '<li>' + '<a href="?uri=' + node_identifier[this.curnode.label] + '">' + this.curnode.label + '</a>';

		/*
		if (this.curnode.sibling) {
			html += '<li>' + this.curnode.label;
		} else {
			html += '<li>' + this.curnode.label;		
		}
		*/
		
		if (this.curnode.child)
		{
			html += '<ul>';
			stack.push(this.curnode);
			this.curnode = this.curnode.child;
		}
		else
		{
			while (stack.length > 0 && this.curnode.sibling == null)
			{
				html += '</li>';
				html += '</ul>';
				this.curnode = stack.pop();
			}
			
			if (stack.length == 0)
			{
				this.curnode = null;
			}
			else
			{
				html += '</li>';
				this.curnode = this.curnode.sibling;
			}
		}
	}
	html += '</ul>';	
	html += '</div>';

	return html;
}