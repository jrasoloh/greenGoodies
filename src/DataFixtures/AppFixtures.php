<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $categories = ['Salle de bain', 'Alimentation', 'Accessoires'];
        $categoryEntities = [];

        foreach ($categories as $catName) {
            $category = new Category();
            $category->setName($catName);
            $manager->persist($category);
            $categoryEntities[$catName] = $category;
        }

        $productsData = [
            [
                'name' => "Kit d'hygiène recyclable",
                'short' => "Pour une salle de bain éco-friendly",
                'long' => "Un ensemble complet pour transformer votre routine matinale. Ce kit comprend des essentiels de salle de bain fabriqués à partir de matériaux durables et biodégradables.\n\nFini le plastique à usage unique, adoptez une démarche zéro déchet sans compromis sur la qualité et le confort d'utilisation. Chaque élément a été pensé pour minimiser votre empreinte écologique tout en sublimant votre intérieur.",
                'price' => 2499,
                'cat' => 'Salle de bain',
                'picture' => 'kit-hygiene.png'
            ],
            [
                'name' => "Shot Tropical",
                'short' => "Fruits frais, pressés à froid",
                'long' => "Une explosion de saveurs exotiques dans un format compact. Nos shots sont pressés à froid à partir de fruits 100% bio pour conserver un maximum de vitamines, d'enzymes et de nutriments.\n\nIdéal pour un coup de boost naturel au saut du lit ou avant une séance de sport. Conditionné dans une petite bouteille en verre entièrement recyclable.",
                'price' => 450,
                'cat' => 'Alimentation',
                'picture' => 'shot-tropical.png'
            ],
            [
                'name' => "Gourde en bois",
                'short' => "50cl, bois d'olivier",
                'long' => "Élégante, nomade et robuste, cette gourde de 50cl est votre alliée hydratation au quotidien. Son revêtement extérieur en bois d'olivier véritable lui confère un design unique et chaleureux.\n\nSon intérieur en acier inoxydable de qualité alimentaire maintient vos boissons à la température idéale, qu'elles soient chaudes ou froides. Zéro BPA, 100% style.",
                'price' => 1890,
                'cat' => 'Accessoires',
                'picture' => 'gourde-bois.png'
            ],
            [
                'name' => "Disques Démaquillants x3",
                'short' => "Solution efficace pour vous démaquiller en douceur",
                'long' => "Remplacez définitivement vos cotons jetables par ces disques démaquillants lavables et réutilisables. Confectionnés en coton bio ultra-doux, ils nettoient votre peau en profondeur sans l'irriter, même pour les zones sensibles comme le contour des yeux.\n\nLivrés par lot de 3, ils sont faciles à entretenir (lavables en machine) et dureront des années, réduisant considérablement vos déchets quotidiens.",
                'price' => 1990,
                'cat' => 'Salle de bain',
                'picture' => 'disques-demaquillants.png'
            ],
            [
                'name' => "Bougie Lavande & Patchouli",
                'short' => "Cire naturelle",
                'long' => "Créez une atmosphère relaxante et saine dans votre intérieur. Coulée à la main de manière artisanale, cette bougie utilise exclusivement de la cire végétale naturelle et une mèche en coton sans plomb.\n\nLes huiles essentielles pures de lavande et de patchouli diffusent un parfum délicat et apaisant, parfait pour vos moments de détente après une longue journée.",
                'price' => 3200,
                'cat' => 'Accessoires',
                'picture' => 'bougie-lavande.png'
            ],
            [
                'name' => "Brosse à dent",
                'short' => "Bois de hêtre rouge issu de forêts gérées durablement",
                'long' => "Un sourire éclatant, une planète préservée. Le manche ergonomique de cette brosse à dents est taillé dans du bois de hêtre rouge issu de forêts européennes éco-gérées.\n\nSes poils souples en nylon recyclable assurent un brossage efficace tout en respectant l'émail de vos dents et la sensibilité de vos gencives. L'alternative écologique indispensable à avoir dans son gobelet.",
                'price' => 540,
                'cat' => 'Salle de bain',
                'picture' => 'brosse-a-dent.png'
            ],
            [
                'name' => "Kit couvert en bois",
                'short' => "Revêtement Bio en olivier & sac de transport",
                'long' => "Ne soyez plus jamais pris au dépourvu lors de vos déjeuners nomades. Ce set de couverts légers et résistants (fourchette, couteau, cuillère) est sculpté dans du bois d'olivier naturel, traité avec une huile végétale bio.\n\nIl est fourni avec sa jolie pochette de transport en lin naturel. L'ensemble parfait pour les repas au bureau, les pique-niques ou les voyages, pour dire adieu au plastique jetable.",
                'price' => 1230,
                'cat' => 'Accessoires',
                'picture' => 'kit-couvert.png'
            ],
            [
                'name' => "Nécessaire, déodorant Bio",
                'short' => "50ml déodorant à l'eucalyptus",
                'long' => "Déodorant Nécessaire, une formule révolutionnaire composée exclusivement d'ingrédients naturels pour une protection efficace et bienfaisante.\n\nChaque flacon de 50 ml renferme le secret d'une fraîcheur longue durée, sans compromettre votre bien-être ni l'environnement. Conçu avec soin, ce déodorant allie le pouvoir antibactérien des extraits de plantes aux vertus apaisantes des huiles essentielles, assurant une sensation de confort toute la journée.\n\nGrâce à sa formule non irritante et respectueuse de votre peau, Nécessaire offre une alternative saine aux déodorants conventionnels, tout en préservant l'équilibre naturel de votre corps.",
                'price' => 850,
                'cat' => 'Salle de bain',
                'picture' => 'deodorant-bio.png'
            ],
            [
                'name' => "Savon Bio",
                'short' => "Thé, Orange & Girofle",
                'long' => "Un véritable soin purifiant et tonifiant pour le corps et les mains. Saponifié à froid pour préserver toutes les propriétés hydratantes de ses ingrédients naturels, ce savon est un concentré de bienfaits.\n\nIl associe la douceur antioxydante du thé aux notes chaudes et revigorantes de l'orange et du clou de girofle. Sa mousse onctueuse nettoie en douceur et laisse la peau délicatement parfumée.",
                'price' => 1890,
                'cat' => 'Salle de bain',
                'picture' => 'savon-bio.png'
            ]
        ];

        $productEntities = [];
        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name'])
                ->setShortDescription($data['short'])
                ->setLongDescription($data['long'])
                ->setPrice($data['price'])
                ->setStock(rand(10, 50))
                ->setPicture($data['picture'])
                ->setCategory($categoryEntities[$data['cat']]);

            $manager->persist($product);
            $productEntities[] = $product;
        }

        $user = new User();
        $user->setEmail('test@test.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setIsApiKeyActive(true);
        $user->setApiKey('gg_lk_' . bin2hex(random_bytes(16)));
        // ROLE_API est dérivé dynamiquement de isApiKeyActive dans User::getRoles(),
        // il ne faut donc pas le stocker en dur dans la colonne roles.

        // Mot de passe court "test"
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'test');
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        for ($i = 1; $i <= 3; $i++) {
            $order = new Order();
            $order->setCustomer($user);
            $order->setCreatedAt(new \DateTimeImmutable("-{$i} days"));
            $order->setReference('CMD-' . date('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT));
            $order->setStatus('DELIVERED');

            /** @var Product $randomProduct */
            $randomProduct = $productEntities[array_rand($productEntities)];

            $orderLine = new OrderLine();
            $orderLine->setProduct($randomProduct); // Liaison propre
            $orderLine->setProductName($randomProduct->getName());
            $orderLine->setProductPrice($randomProduct->getPrice());
            $orderLine->setQuantity(rand(1, 2));

            $order->addOrderLine($orderLine);

            $manager->persist($orderLine);
            $manager->persist($order);
        }

        $manager->flush();
    }
}
