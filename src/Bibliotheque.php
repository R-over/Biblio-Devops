<?php

class Bibliotheque
{
    /** @var Livre[] */
    private array $livres = [];

    public function ajouter(Livre $l): void
    {
        if ($this->trouver($l->getIsbn()) !== null) {
            throw new Exception(
                "Un livre avec l'ISBN {$l->getIsbn()} existe deja."
            );
        }
        $this->livres[] = $l;
    }

    public function trouver(string $isbn): ?Livre
    {
        foreach ($this->livres as $livre) {
            if ($livre->getIsbn() === $isbn) {
                return $livre;
            }
        }
        return null;
    }

    public function tous(): array
    {
        return $this->livres;
    }

    public function compter(): int
    {
        return count($this->livres);
    }

    public function rechercher(string $mot): array
    {
        $resultats = [];
        foreach ($this->livres as $livre) {
            if (
                stripos($livre->getTitre(), $mot) !== false ||
                stripos($livre->getAuteur(), $mot) !== false
            ) {
                $resultats[] = $livre;
            }
        }
        return $resultats;
    }

    // Nouvelle fonctionnalite : statistiques du catalogue
    public function statistiques(): array
    {
        $total = $this->compter();
        $disponibles = 0;

        foreach ($this->livres as $livre) {
            if ($livre->estDisponible()) {
                $disponibles++;
            }
        }

        $empruntes = $total - $disponibles;

        $taux = $total === 0
            ? 0.0
            : ($disponibles / $total) * 100;

        return [
            'total' => $total,
            'disponibles' => $disponibles,
            'empruntes' => $empruntes,
            'taux_disponibilite' => $taux
        ];
    }
}