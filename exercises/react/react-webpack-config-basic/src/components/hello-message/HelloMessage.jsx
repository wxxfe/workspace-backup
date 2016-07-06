import * as React from "react";

const HelloStyle = {
  s1:{
	  color: 'red'
  },
  s2:{
	  fontSize: 38
  }
};
export default class HelloMessage extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return <div style={Object.assign({},HelloStyle.s1,HelloStyle.s2)} >Hello {this.props.name} !</div>;
  }
}