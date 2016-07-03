var HelloStyle = {
  color: 'red'
};
var HelloMessage = React.createClass({
  componentDidMount: function() {
    console.log(this.refs.placeholder);
  },
  render: function() {  
    return	<div style={HelloStyle} ref="placeholder">
				Hello {this.props.name}!
			</div>;
  }
});

ReactDOM.render(<HelloMessage name="World" />, document.getElementById("mountNode"));
