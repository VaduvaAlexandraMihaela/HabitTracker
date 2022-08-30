<?php

namespace App\Repository;

use App\Entity\Food;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Sodium\compare;

/**
 * @extends ServiceEntityRepository<Food>
 *
 * @method Food|null find($id, $lockMode = null, $lockVersion = null)
 * @method Food|null findOneBy(array $criteria, array $orderBy = null)
 * @method Food[]    findAll()
 * @method Food[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function add(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function compareDates($date1, $date2): bool|int
    {
        return strtotime($date1->getTimeStamp()->format('%d')) - strtotime($date2->getTimeStamp()->format('%d'));
    }

    public function getAllFoodsByUserId($userId) : array
    {
        $foods = $this->findBy(array('user_id' => $userId));
        return $foods;
    }

    public function getTotalCaloriesByDate($userId) : array
    {
        $foods = $this->getAllFoodsByUserId($userId);
        $totalCalories = 0;
        $allCalories = array();
        foreach($foods as $food){
           foreach($foods as $secondFood){
               if(date_diff($secondFood->getTimeStamp(),$food->getTimeStamp())->format('%d') == 0){
                   $totalCalories += $secondFood->getCalories();
               }
           }
            array_push($allCalories,array($totalCalories => $food->getTimeStamp()));
            $totalCalories = 0;
        }
        $allCalories = array_map("unserialize", array_unique(array_map("serialize", $allCalories)));

        return $allCalories;
    }

//    /**
//     * @return Food[] Returns an array of Food objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Food
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
