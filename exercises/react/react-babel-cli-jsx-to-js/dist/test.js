var HelloStyle = {
  color: 'red'
};
var HelloMessage = React.createClass({
  displayName: "HelloMessage",

  componentDidMount: function () {
    console.log(this.refs.placeholder);
  },
  render: function () {
    return React.createElement(
      "div",
      { style: HelloStyle, ref: "placeholder" },
      "Hello ",
      this.props.name,
      "!"
    );
  }
});

ReactDOM.render(React.createElement(HelloMessage, { name: "World" }), document.getElementById("mountNode"));