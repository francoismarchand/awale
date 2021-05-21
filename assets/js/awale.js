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
                    currentPlayer: data.currentPlayer,
                    players: data.players,
                    winner: data.winner
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
                    <h2>En attente de l'adversaire.</h2>
                    <span className="game-link">
                        {window.location.href}
                    </span>
                    <p>
                        Partagez ce lien pour inviter votre adversaire.
                    </p>
                 </div>
            );
        }

        let currentPlayerScore = this.state.scores[this.pointOfView];
        let opponentScore = this.state.scores[this.pointOfView == 1 ? 0 : 1];
        let message = (<p>Votre adversaire réfléchit...</p>);

        if (this.state.players[this.state.currentPlayer] == this.props.player) {
            message = (<strong>C'est votre tour</strong>);
        }

        if (this.state.status == 'finished') {
            if (this.state.winner == this.props.player) {
                message = (<strong>Vous avez gagné !</strong>);
            } else {
                message = (<strong>Votre adversaire a gagné la partie</strong>);
            }
        }
        
        console.log(this.props);
        console.log(this.state);
        
        return (
            <div className="game">
                <div className="game-message">
                    {message}
                </div>
                <data className="game-score game-score-opponent">{opponentScore}</data>
                <div className="game-board">
                    <Board
                        pointOfView={this.pointOfView}
                        holes={this.state.board}
                        currentPlayer={this.state.currentPlayer}
                        onClick={index => this.onClick(index)}
                    />
                </div>
                <data className="game-score game-score-current">{currentPlayerScore}</data>
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
