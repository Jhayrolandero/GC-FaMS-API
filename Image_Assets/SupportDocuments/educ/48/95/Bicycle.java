public class Bicycle{

	private int cadence;
	private int gear;
	private int speed;

	private int id;

	private static int numOfBicycle = 0;

	public Bicycle(int startCadence, int startSpeed, int startGear){
		gear = startGear;
		cadence = startCadence;
		speed = startSpeed;

		id = ++numOfBicycle;
	}

	public int getID(){
		return id;
	}

	public static void main(String[]args){
		Bicycle bicycle1 = new Bicycle(10, 10, 10);
		System.out.println(bicycle1.getID());
	}
}
