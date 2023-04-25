<?php

namespace App\Service;

use App\Entity\Player;
use Doctrine\Common\Collections\ArrayCollection;

use SplObjectStorage;
use SplObserver;
use SplSubject;

class PlayerSwitcher implements SplSubject
{
    private ?Player $currentPlayer = null;

    public function __construct(
        private SplObjectStorage $players = new SplObjectStorage(),
        private SplObjectStorage $observers = new SplObjectStorage()
    ) {
    }

    public function setPlayers(SplObjectStorage $players)
    {
        $this->players->addAll($players);
        $this->currentPlayer = $this->current();
    }

    public function addPlayer(Player $player)
    {
        $this->players->attach($player);
    }

    public function current(): Player
    {
        return $this->players->current();
    }

    public function next(): void
    {
        $this->players->next();
        if (!$this->players->valid()) {
            $this->players->rewind();
        }
    }

    public function prev(): void
    {
        $actual = $this->players->current();

        $this->players->rewind();
        while ($this->players->current() === $actual) {
            $this->players->next();
        }
    }

    public function getPlayers(): SplObjectStorage
    {
        return $this->players;
    }

    public function getNextPlayer()
    {
        foreach ($this->players as $player) {
            if ($player === $this->getCurrentPlayer()) {
                $this->next();
                return $this->current();
            }
        }
    }

    public function findSelectionnable(string $id)
    {
        $players = clone $this->getPlayers();
        foreach ($players as $player) {
            foreach ($player->getCards() as $card) {
                if ($card->getIdentifier() === $id) {
                    return $card;
                }
            }
            foreach ($player->getActions() as $action) {
                if ($action->getIdentifier() === $id) {
                    return $action;
                }
            }
        }
    }

    /**
     * Get the value of currentPlayer
     */
    public function getCurrentPlayer(): ?Player
    {
        if ($this->currentPlayer === null) {
            $this->currentPlayer = $this->current();
        }

        return $this->currentPlayer;
    }

    /**
     * Set the value of currentPlayer
     */
    public function setCurrentPlayer(Player $currentPlayer): self
    {
        $this->currentPlayer = $currentPlayer;

        return $this;
    }

    public function switch(): self
    {
        $this->notify();
        $this->setCurrentPlayer($this->getNextPlayer());

        return $this;
    }



    public function getOtherPlayers()
    {
        foreach ($this->players as $player) {
            if ($player !== $this->getCurrentPlayer()) {
                $otherPlayers[] = $player;
            }
        }

        return $otherPlayers;
    }

    public function getOtherPlayersCards()
    {
        $cards = new ArrayCollection();
        foreach ($this->players as $player) {
            if ($player !== $this->getCurrentPlayer()) {
                foreach ($player->getCards() as $card) {
                    $cards->add($card);
                }
            }
        }

        return $cards;
    }

    // Pattern observer
    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->getCurrentPlayer()->getCards() as $card) {
            $caracteristic = $card->getCaracteristic();

            if ($caracteristic?->getIntelligenceModifier() instanceof SplObserver) {
                $this->attach($caracteristic->getIntelligenceModifier());
            }
        }

        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
