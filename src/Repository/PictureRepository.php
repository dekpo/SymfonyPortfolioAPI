<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    private $manager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Picture::class);
        $this->manager = $manager;
    }
    public function savePicture($url,$author,$title,$description,$date){
        $picture = new Picture();
        $picture->setUrl($url)
                ->setAuthor($author)
                ->setTitle($title)
                ->setDescription($description)
                ->setDateCreated($date)
                ->setDateUpdated($date);
       $this->manager->persist($picture);
       $this->manager->flush();
    }
    public function updatePicture(Picture $picture,$request): Picture
    {
        $url = $request->request->get('url');
        empty($url)? null : $picture->setUrl($url);
        $author = $request->request->get('author');
        empty($author)? null : $picture->setAuthor($author);
        $title = $request->request->get('title');
        empty($title)? null : $picture->setTitle($title);
        $description = $request->request->get('description');
        empty($description)? null : $picture->setDescription($description);

        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');
        $picture->setDateUpdated($date);

        $this->manager->persist($picture);
        $this->manager->flush();

        return $picture;
    }
    public function removePicture(Picture $picture){
        $this->manager->remove($picture);
        $this->manager->flush();
    }
    // /**
    //  * @return Picture[] Returns an array of Picture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Picture
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
