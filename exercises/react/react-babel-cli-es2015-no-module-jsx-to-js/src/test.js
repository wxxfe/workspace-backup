const HelloStyle = {
  s1:{
	  color: 'red'
  },
  s2:{
	  fontSize: 18
  }
};
class HelloMessage extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return <div style={Object.assign(HelloStyle.s1,HelloStyle.s2)} >Hello {this.props.name}!</div>;
  }
}

ReactDOM.render(<HelloMessage name="World" />, document.getElementById("mountNode"));
