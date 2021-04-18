import React from "react";
import ReactDom from "react-dom";
import Board from "./board";

class Game extends React.Component {
    //TODO, rendre inactif les trous de l'adversaire
    //TODO, afficahge inversé pour le deuxième joueur
    constructor(props) {
        super(props);
        this.initWebsocket(this.props.url, this.props.uuid, this.props.player);
        this.pointOfView = 0;
        this.state = {
            board: [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
            scores: [0, 0],
            stepNumber: 0,
            currentPlayer: 0,
            status: 'waiting',
        };
    }

    initWebsocket(url, uuid, player)
    {
        this.webSocket = new WebSocket(url + "/" + uuid + "/" + player);
        var that = this;

        this.webSocket.onopen = function() {
            this.onmessage = function(event) {
                try {
                    var data = JSON.parse(event.data);
                } catch (e) {
                    return;
                }                

                console.log(data);

                
                if (that.props.player == data.players[1]) {
                    that.pointOfView = 1;        
                }

                if (that.state.status == 'waiting' && data.status == 'ready') {
                    //TODO notification que le jeux commence
                }

                that.setState({
                    board: data.board,
                    scores: data.scores,
                    status: data.status,
                    currentPlayer: data.currentPlayer
                });
                
            };
            this.onclose = function() {};
            this.onerror = function() {};
        }
    }

    onClick(holePlayed) {        
        this.webSocket.send(JSON.stringify({
            'game': this.props.uuid, 
            'player': this.state.currentPlayer, 
            'case': holePlayed,
            'action': 'play'
        }));
    }

    render() {

        if (this.state.status == 'waiting') {
            return (
                <div className="message">
                    En attente joueur
                    Lien : <strong>{window.location.href}</strong>
                </div>
            );
        }

        return (
            <div className="game">
                <div className="game-board">
                    <Board
                        pointOfView={this.pointOfView}
                        holes={this.state.board}
                        currentPlayer={this.state.currentPlayer}
                        onClick={index => this.onClick(index)}
                    />
                </div>
                <div className="game-info">
                    <div>{this.state.status}</div>
                    <div>Joueur 1 : {this.state.scores[0]}</div>
                    <div>Joueur 2 : {this.state.scores[1]}</div>
                    <div>Qui suis-je : {this.props.player}</div>
                    <div>&Agrave; qui de jouer : {this.state.currentPlayer}</div>
                </div>
            </div>
        );
    }
}

//TODO sortir ça dans un autre fichier
let partie = document.getElementById("js-awale");
ReactDom.render(<Game 
        uuid={partie.dataset.uuid} 
        url={partie.dataset.url} 
        player={partie.dataset.player}/>, 
    partie
);
