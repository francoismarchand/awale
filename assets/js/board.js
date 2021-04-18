import React from "react";
import Hole from "./hole";

class Board extends React.Component {
    renderHole(i) {
        return (
            <Hole
                value={this.props.holes[i]}
                onClick={() => this.props.onClick(i)}
            />
        );
    }

    render() {
        return (
            <div className="board">
                <div className="range">
                    {this.renderHole(11)}
                    {this.renderHole(10)}
                    {this.renderHole(9)}
                    {this.renderHole(8)}
                    {this.renderHole(7)}
                    {this.renderHole(6)}
                </div>
                <div className="range">
                    {this.renderHole(0)}
                    {this.renderHole(1)}
                    {this.renderHole(2)}
                    {this.renderHole(3)}
                    {this.renderHole(4)}
                    {this.renderHole(5)}
                </div>
            </div>
        );
    }
}

export default Board;