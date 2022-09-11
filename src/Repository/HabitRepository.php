<?php

namespace App\Repository;

use App\Entity\Habit;
use App\Entity\RecHabit;
use ContainerNRcGcH4\getUserRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Habit>
 *
 * @method Habit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habit[]    findAll()
 * @method Habit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habit::class);
    }

    /**
     * @throws \Exception
     */
    public function add(Habit $entity, RecHabitRepository $recHabitRepository, bool $flush = false): void
    {

        $entity = $this->setTimeEndWithInterval($entity);

        $this->getEntityManager()->persist($entity);
        $recHabit = new RecHabit();
        $recHabit->setName($entity->getName());
        $recHabit->setTimeStart($entity->getTimeStart());
        $recHabit->setDuration($entity->getTimeSpent());
        $recHabitRepository->add($recHabit);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws \Exception
     */
    public function setTimeEndWithInterval($habit) : Habit{
        $timeStart = $habit->getTimeStart();
        $duration = $habit->getTimeSpent();
        $minutes =  strval($duration);
        $timeEnd = clone $timeStart;
        $timeEnd = $timeEnd->modify("+{$minutes} minutes");
        $habit->setTimeEnd($timeEnd);

        return $habit;
    }

    public function remove(Habit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getHabitsLastWeek($userId) : array
    {
        $habits = $this->getAllHabitsByUserId($userId);
        $habitsLastWeek = array();
        foreach($habits as $habit){
            $timeStart = $habit->getTimeStart();
            $today = new DateTime('now');
            if(date_diff($timeStart,$today)->format('%d') >= 7 && date_diff($timeStart,$today)->format('%d') <= 14){
                array_push($habitsLastWeek,$habit);
            }
        }

        return $habitsLastWeek;
    }

    public function getHabitsToday($userId) : array
    {
        $habits = $this->getAllHabitsByUserId($userId);
        $habitsToday = array();
        foreach($habits as $habit){
            $timeStart = $habit->getTimeStart();
            $today = new DateTime('now');
            if(date_diff($timeStart,$today)->format('%d') == 0){
                array_push($habitsToday,$habit);
            }
        }

        return $habitsToday;
    }

    public function getHabitsYesterday($userId) : array
    {
        $habits = $this->getAllHabitsByUserId($userId);
        $habitsYesterday = array();
        foreach($habits as $habit){
            $timeStart = $habit->getTimeStart();
            $today = new DateTime('now');
            if(date_diff($timeStart,$today)->format('%d') == 1){
                array_push($habitsYesterday,$habit);
            }
        }

        return $habitsYesterday;
    }

    public function getAllHabitsByUserId($userId) : array
    {
        $habits = $this->findBy(array('UserId' => $userId));
        return $habits;
    }



//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
